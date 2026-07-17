<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\SubscriptionPlan;
use App\Models\UserSubscription;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SubscriptionApiController extends Controller
{
    /**
     * List all subscription plans.
     */
    public function index(): JsonResponse
    {
        $plans = SubscriptionPlan::where('is_active', true)->get();

        return response()->json([
            'plans' => $plans,
        ]);
    }

    /**
     * Subscribe user to a plan.
     */
    public function subscribe(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'subscription_plan_id' => 'required|exists:subscription_plans,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = $request->user();
        $plan = SubscriptionPlan::find($request->subscription_plan_id);

        // Deactivate older active subscriptions
        UserSubscription::where('user_id', $user->id)
            ->where('status', 'active')
            ->update(['status' => 'expired']);

        // Create new active subscription
        $subscription = UserSubscription::create([
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

        return response()->json([
            'message' => 'Subscribed successfully.',
            'subscription' => $subscription->load('subscriptionPlan'),
        ], 201);
    }
}
