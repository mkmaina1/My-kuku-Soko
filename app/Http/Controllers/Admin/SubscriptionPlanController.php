<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\VeterinarySubscription;
use Illuminate\Http\Request;

class SubscriptionPlanController extends Controller
{
    /**
     * Display a listing of subscription plans
     */
    public function index(Request $request)
    {
        $query = SubscriptionPlan::withCount('subscriptions');

        if ($request->has('filter') && $request->filter === 'pending') {
            $plans = $query->get();
            return view('admin.subscriptions.index', [
                'plans' => $plans,
                'pendingCount' => VeterinarySubscription::where('payment_verified', false)
                    ->where('status', 'pending')
                    ->count()
            ]);
        }

        $plans = $query->orderBy('price')->get();
        return view('admin.subscriptions.index', compact('plans'));
    }

    /**
     * Show form for creating a new plan
     */
    public function create()
    {
        return view('admin.subscriptions.create');
    }

    /**
     * Store a newly created plan
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|in:monthly,yearly',
            'features_json' => 'required|json',
            'is_active' => 'boolean'
        ]);

        $features = json_decode($request->features_json, true);

        SubscriptionPlan::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'features' => $features,
            'is_active' => $request->is_active ?? true
        ]);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan created successfully!');
    }

    /**
     * Display the specified plan
     */
    public function show(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptions = $subscriptionPlan->subscriptions()
            ->with(['user', 'plan', 'verifiedBy'])
            ->latest()
            ->paginate(20);

        return view('admin.subscriptions.show', [
            'plan' => $subscriptionPlan,
            'subscriptions' => $subscriptions
        ]);
    }

    /**
     * Show form for editing a plan
     */
    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        return view('admin.subscriptions.edit', ['plan' => $subscriptionPlan]);
    }

    /**
     * Update the specified plan
     */
    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:subscription_plans,slug,' . $subscriptionPlan->id,
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'duration' => 'required|in:monthly,yearly',
            'features_json' => 'required|json',
            'is_active' => 'boolean'
        ]);

        $features = json_decode($request->features_json, true);

        $subscriptionPlan->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'description' => $request->description,
            'price' => $request->price,
            'duration' => $request->duration,
            'features' => $features,
            'is_active' => $request->is_active ?? true
        ]);

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan updated successfully!');
    }

    /**
     * Remove the specified plan
     */
    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        if ($subscriptionPlan->subscriptions()->where('status', 'active')->exists()) {
            return redirect()->route('admin.subscriptions.index')
                ->with('error', 'Cannot delete plan with active subscriptions. Deactivate it instead.');
        }

        $subscriptionPlan->delete();

        return redirect()->route('admin.subscriptions.index')
            ->with('success', 'Subscription plan deleted successfully!');
    }

    /**
     * Toggle plan active status
     */
    public function toggleStatus(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->update([
            'is_active' => !$subscriptionPlan->is_active
        ]);

        $status = $subscriptionPlan->is_active ? 'activated' : 'deactivated';

        return redirect()->route('admin.subscriptions.index')
            ->with('success', "Subscription plan {$status} successfully!");
    }

    /**
     * Show subscription statistics
     */
    public function statistics()
    {
        $stats = [
            'total_plans' => SubscriptionPlan::count(),
            'active_plans' => SubscriptionPlan::where('is_active', true)->count(),
            'total_subscriptions' => VeterinarySubscription::count(),
            'active_subscriptions' => VeterinarySubscription::where('status', 'active')->count(),
            'revenue' => VeterinarySubscription::where('status', 'active')->sum('amount_paid'),
            'by_plan' => SubscriptionPlan::withCount('subscriptions')->get()
        ];

        $recentSubscriptions = VeterinarySubscription::with(['user', 'plan'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.subscriptions.statistics', compact('stats', 'recentSubscriptions'));
    }

    /**
     * Verify payment for a subscription
     */

public function verifyPayment(VeterinarySubscription $subscription)
{
    // Check if already verified
    if ($subscription->payment_verified) {
        return redirect()->route('admin.subscriptions.show', $subscription->plan->id)
            ->with('info', 'Payment already verified for this subscription.');
    }

    // Update subscription
    $subscription->update([
        'payment_verified' => true,
        'verified_at' => now(),
        'verified_by' => auth()->id(),
        'status' => 'active'
    ]);

    // Update user
    $user = $subscription->user;
    $plan = $subscription->plan;

    if ($plan) {
        $user->update([
            'has_active_subscription' => true,
            'subscription_plan' => $plan->slug,
            'subscription_expires_at' => now()->addMonth(),
            'subscription_features' => $plan->features,
        ]);
    }

    return redirect()->route('admin.subscriptions.show', $subscription->plan->id)
        ->with('success', 'Payment verified and subscription activated successfully!');
}
}
