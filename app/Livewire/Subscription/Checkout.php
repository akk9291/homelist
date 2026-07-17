<?php

namespace App\Livewire\Subscription;

use App\Models\SubscriptionPlan;
use App\Models\Payment;
use App\Models\UserSubscription;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Checkout extends Component
{
    public $plans = [];
    public ?int $selectedPlanId = null;
    public string $gateway = 'razorpay'; // razorpay, phonepe, bank_transfer, offline
    public string $utrNumber = '';
    public string $notes = '';

    public function mount()
    {
        $this->plans = SubscriptionPlan::where('is_active', true)->get();
        if ($this->plans->isNotEmpty()) {
            $this->selectedPlanId = $this->plans->first()->id;
        }
    }

    public function processCheckout(PaymentService $paymentService)
    {
        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to purchase a subscription.');
        }

        $plan = SubscriptionPlan::find($this->selectedPlanId);
        if (!$plan) {
            session()->flash('error', 'Selected plan is invalid.');
            return;
        }

        $amount = $plan->price;
        $gst = $amount * 0.18; // 18% GST
        $total = $amount + $gst;

        if ($this->gateway === 'bank_transfer') {
            $this->validate([
                'utrNumber' => 'required|string|unique:payments,utr_number',
                'notes' => 'nullable|string',
            ]);

            $payment = $paymentService->processBankTransfer(
                $user->id,
                $plan->id,
                $amount,
                $this->utrNumber,
                $this->notes
            );

            session()->flash('success', 'UTR reference submitted successfully! Admin will verify and activate your plan shortly.');
            return redirect()->route('dashboard');
        }

        // Mock payment processing for Razorpay and PhonePe
        $transactionId = 'tx_' . time() . '_' . rand(100, 999);
        $payment = Payment::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
            'amount' => $amount,
            'gst_amount' => $gst,
            'total_amount' => $total,
            'payment_gateway' => $this->gateway,
            'payment_status' => 'completed', // instantly complete in mock mode
            'transaction_id' => $transactionId,
            'invoice_number' => 'INV-' . time() . '-' . rand(100, 999),
            'notes' => 'Instantly processed mock checkout.',
        ]);

        // Deactivate older active subscriptions
        UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        // Grant new subscription
        UserSubscription::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $plan->id,
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

        session()->flash('success', "Subscribed to {$plan->name} successfully!");
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.subscription.checkout')->layout('layouts.app');
    }
}
