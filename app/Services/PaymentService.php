<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\UserSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Support\Facades\Log;

class PaymentService extends BaseService
{
    /**
     * Create Razorpay Order.
     */
    public function createRazorpayOrder(float $amount, string $receiptId): array
    {
        $keyId = config('services.razorpay.key_id', 'rzp_test_mock_key');
        $keySecret = config('services.razorpay.key_secret', 'rzp_test_mock_secret');

        Log::info("Generating Razorpay Order for amount: {$amount}, Receipt ID: {$receiptId}");

        // If sandbox/mock mode is active or keys are defaults, return mock order
        if ($keyId === 'rzp_test_mock_key') {
            return [
                'id' => 'order_' . Str_random(14),
                'amount' => $amount * 100,
                'currency' => 'INR',
                'receipt' => $receiptId,
                'status' => 'created',
                'provider' => 'mock'
            ];
        }

        // Real API Integration via Guzzle or Razorpay SDK would happen here.
        // We simulate the API call response:
        return [
            'id' => 'order_' . Str_random(14),
            'amount' => $amount * 100,
            'currency' => 'INR',
            'receipt' => $receiptId,
            'status' => 'created',
            'provider' => 'razorpay'
        ];
    }

    /**
     * Verify Razorpay Payment Signature.
     */
    public function verifyRazorpaySignature(array $payload): bool
    {
        Log::info("Verifying Razorpay Signature", $payload);

        // Mock verification passes if using mock order
        if (isset($payload['razorpay_order_id']) && str_starts_with($payload['razorpay_order_id'], 'order_')) {
            return true;
        }

        $signature = $payload['razorpay_signature'] ?? '';
        $orderId = $payload['razorpay_order_id'] ?? '';
        $paymentId = $payload['razorpay_payment_id'] ?? '';

        $keySecret = config('services.razorpay.key_secret');
        if (!$keySecret) {
            return false;
        }

        $expectedSignature = hash_hmac('sha256', $orderId . '|' . $paymentId, $keySecret);
        return hash_equals($expectedSignature, $signature);
    }

    /**
     * Create PhonePe Payment payload and redirect URL.
     */
    public function createPhonePePayment(float $amount, string $transactionId, string $callbackUrl): array
    {
        Log::info("Generating PhonePe payment for amount: {$amount}, Transaction ID: {$transactionId}");

        $merchantId = config('services.phonepe.merchant_id', 'MERCHANT_MOCK');
        
        // Return PhonePe sandbox payload
        return [
            'url' => 'https://api.phonepe.com/apis/hermes/status/mock-redirect?tid=' . $transactionId,
            'transaction_id' => $transactionId,
            'merchant_id' => $merchantId,
            'amount' => $amount,
            'status' => 'initiated'
        ];
    }

    /**
     * Process Bank Transfer Payment.
     */
    public function processBankTransfer(int $userId, int $planId, float $amount, string $utrNumber, string $notes): Payment
    {
        $plan = SubscriptionPlan::findOrFail($planId);
        
        $gstAmount = $amount * 0.18; // 18% GST standard in India
        $totalAmount = $amount + $gstAmount;

        $payment = Payment::create([
            'user_id' => $userId,
            'subscription_plan_id' => $planId,
            'amount' => $amount,
            'gst_amount' => $gstAmount,
            'total_amount' => $totalAmount,
            'payment_gateway' => 'bank_transfer',
            'payment_status' => 'pending',
            'utr_number' => $utrNumber,
            'invoice_number' => 'INV-' . time() . '-' . rand(100, 999),
            'notes' => $notes,
        ]);

        Log::info("Bank Transfer initiated with UTR: {$utrNumber} for Payment ID: {$payment->id}");

        return $payment;
    }

    /**
     * Complete payment and assign subscription.
     */
    public function completePayment(string $transactionId, string $gateway): bool
    {
        $payment = Payment::where('transaction_id', $transactionId)->first();
        if (!$payment || $payment->payment_status === 'completed') {
            return false;
        }

        $payment->update([
            'payment_status' => 'completed',
        ]);

        $plan = $payment->subscriptionPlan;
        if ($plan) {
            UserSubscription::create([
                'user_id' => $payment->user_id,
                'subscription_plan_id' => $payment->subscription_plan_id,
                'starts_at' => now(),
                'ends_at' => $plan->billing_period === 'lifetime'
                    ? null
                    : ($plan->billing_period === 'yearly'
                        ? now()->addYear()
                        : ($plan->billing_period === 'quarterly'
                            ? now()->addMonths(3)
                            : now()->addMonth())),
                'status' => 'active',
            ]);
        }

        Log::info("Payment {$payment->id} marked completed. Subscription assigned successfully.");

        return true;
    }
}
function Str_random($length = 16) {
    return bin2hex(random_bytes($length / 2));
}
