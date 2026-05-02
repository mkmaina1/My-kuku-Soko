<?php

namespace App\Http\Controllers;

use App\Models\MpesaPayment;
use App\Models\Order;
use App\Models\SubscriptionPlan;
use App\Models\VeterinarySubscription;
use App\Services\MpesaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MpesaController extends Controller
{
    protected $mpesaService;

    public function __construct(MpesaService $mpesaService)
    {
        $this->mpesaService = $mpesaService;
    }

    /**
     * M-Pesa Callback URL
     */
    public function callback(Request $request)
    {
        Log::info('M-Pesa Callback Received', $request->all());

        $callbackData = $request->all();

        if (isset($callbackData['Body']['stkCallback'])) {
            $stkCallback = $callbackData['Body']['stkCallback'];
            $checkoutRequestId = $stkCallback['CheckoutRequestID'];
            $resultCode = $stkCallback['ResultCode'];
            $resultDesc = $stkCallback['ResultDesc'];

            // Find the payment record
            $payment = MpesaPayment::where('checkout_request_id', $checkoutRequestId)->first();

            if ($payment) {
                $payment->update([
                    'result_desc' => $resultDesc,
                    'callback_data' => $callbackData
                ]);

                if ($resultCode == 0) {
                    // Payment successful
                    $metadata = $stkCallback['CallbackMetadata']['Item'] ?? [];

                    $mpesaReceiptNumber = null;
                    $transactionDate = null;

                    foreach ($metadata as $item) {
                        if ($item['Name'] == 'MpesaReceiptNumber') {
                            $mpesaReceiptNumber = $item['Value'];
                        } elseif ($item['Name'] == 'TransactionDate') {
                            $transactionDate = $item['Value'];
                        }
                    }

                    // Update payment record
                    $payment->update([
                        'status' => 'completed',
                        'mpesa_receipt_number' => $mpesaReceiptNumber,
                        'transaction_date' => $transactionDate ? date('Y-m-d H:i:s', strtotime($transactionDate)) : null,
                    ]);

                    // ========== ADD THIS CONDITIONAL LOGIC ==========
                    // Check if this is an order payment or subscription payment
                    if ($payment->order_id) {
                        // Handle order payment
                        $this->handleOrderPayment($payment, $mpesaReceiptNumber);
                    } else {
                        // Handle subscription payment
                        $this->handleSubscriptionPayment($stkCallback, $payment);
                    }
                    // =================================================

                } else {
                    // Payment failed
                    $payment->update(['status' => 'failed']);

                    if ($payment->order_id) {
                        $order = Order::find($payment->order_id);
                        if ($order) {
                            $order->update(['payment_status' => 'failed']);
                        }
                    } else {
                        // Handle failed subscription payment
                        $subscription = VeterinarySubscription::where('checkout_request_id', $checkoutRequestId)->first();
                        if ($subscription) {
                            $subscription->update(['status' => 'failed']);
                        }
                    }
                }
            }
        }

        return response()->json(['ResultCode' => 0, 'ResultDesc' => 'Success']);
    }

    /**
     * Handle order payment
     */
    protected function handleOrderPayment($payment, $mpesaReceiptNumber)
    {
        $order = Order::find($payment->order_id);
        if ($order) {
            $order->update([
                'payment_status' => 'paid',
                'payment_reference' => $mpesaReceiptNumber,
                'status' => 'processing'
            ]);

            Log::info('Order payment completed', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'receipt' => $mpesaReceiptNumber
            ]);
        }
    }

    /**
     * Handle subscription payment
     */
    protected function handleSubscriptionPayment($stkCallback, $payment)
    {
        $checkoutRequestId = $stkCallback['CheckoutRequestID'];

        $subscription = VeterinarySubscription::where('checkout_request_id', $checkoutRequestId)->first();

        if ($subscription && $subscription->status === 'pending') {
            $metadata = $stkCallback['CallbackMetadata']['Item'] ?? [];
            $mpesaReceiptNumber = null;

            foreach ($metadata as $item) {
                if ($item['Name'] == 'MpesaReceiptNumber') {
                    $mpesaReceiptNumber = $item['Value'];
                    break;
                }
            }

            $plan = SubscriptionPlan::find($subscription->subscription_plan_id);

            $subscription->update([
                'status' => 'active',
                'mpesa_receipt' => $mpesaReceiptNumber,
                'starts_at' => now(),
                'expires_at' => now()->addMonth(),
            ]);

            // Update user
            $user = $subscription->user;
            $user->update([
                'has_active_subscription' => true,
                'subscription_plan' => $plan->slug,
                'subscription_expires_at' => now()->addMonth(),
                'subscription_features' => $plan->features,
            ]);

            Log::info('Subscription activated successfully', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'plan' => $plan->name,
                'receipt' => $mpesaReceiptNumber,
                'expires_at' => now()->addMonth()->toDateTimeString()
            ]);
        } else {
            Log::warning('Subscription activation failed', [
                'checkout_request_id' => $checkoutRequestId,
                'subscription_found' => $subscription ? true : false,
                'subscription_status' => $subscription ? $subscription->status : 'N/A'
            ]);
        }
    }

    /**
     * Check payment status
     */
    public function checkStatus($checkoutRequestId)
    {
        $payment = MpesaPayment::where('checkout_request_id', $checkoutRequestId)
            ->with('order')
            ->first();

        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'status' => $payment->status,
            'mpesa_receipt' => $payment->mpesa_receipt_number,
            'payment' => $payment,
            'order' => $payment->order
        ]);
    }
}
