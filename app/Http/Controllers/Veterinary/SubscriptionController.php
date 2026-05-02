<?php

namespace App\Http\Controllers\Veterinary;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\VeterinarySubscription;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SubscriptionController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * Show subscription plans page
     */
    public function index()
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();
        return view('veterinary.subscription.plans', compact('plans'));
    }

    /**
     * Show specific plan details
     */
    public function show($slug)
    {
        $plan = SubscriptionPlan::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('veterinary.subscription.show', compact('plan'));
    }

    /**
     * Process subscription payment
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'plan_id' => 'required|exists:subscription_plans,id',
            'phone_number' => 'required|string',
        ]);

        $user = Auth::user();
        $plan = SubscriptionPlan::findOrFail($request->plan_id);

        // Check if user already has active subscription
        if ($user->has_active_subscription) {
            return response()->json([
                'success' => false,
                'message' => 'You already have an active subscription.'
            ], 400);
        }

        DB::beginTransaction();

        try {
            // Create subscription record
            $subscription = VeterinarySubscription::create([
                'user_id' => $user->id,
                'subscription_plan_id' => $plan->id,
                'plan_name' => $plan->name,
                'amount_paid' => $plan->price,
                'payment_method' => 'mpesa',
                'status' => 'pending',
                'checkout_request_id' => 'SUB-' . strtoupper(Str::random(8))
            ]);

            DB::commit();

            // Initiate M-Pesa payment
            $mpesaResult = $this->mpesaService->stkPush(
                $request->phone_number,
                $plan->price,
                'SUB' . $subscription->id,
                'Subscription: ' . $plan->name . ' Plan',
                $subscription->id
            );

            if ($mpesaResult['success']) {
                $subscription->update([
                    'checkout_request_id' => $mpesaResult['checkout_request_id']
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment prompt sent. Please check your phone.',
                    'subscription' => $subscription,
                    'checkout_request_id' => $mpesaResult['checkout_request_id']
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to initiate payment: ' . ($mpesaResult['message'] ?? 'Unknown error')
                ], 500);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to process subscription: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus($checkoutRequestId)
    {
        $subscription = VeterinarySubscription::where('checkout_request_id', $checkoutRequestId)
            ->with('plan')
            ->first();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Subscription not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $subscription->status,
            'subscription' => $subscription
        ]);
    }
}
