<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserApiController extends Controller
{
    /**
     * Get logged-in user profile.
     */
    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'user' => $request->user()->load(['activeSubscription.subscriptionPlan']),
        ]);
    }

    /**
     * Get user wishlist.
     */
    public function wishlist(Request $request): JsonResponse
    {
        $wishlist = $request->user()->favoriteProperties()->with(['type', 'status', 'city'])->get();

        return response()->json([
            'wishlist' => $wishlist,
        ]);
    }

    /**
     * Add/remove property from wishlist.
     */
    public function toggleWishlist(Request $request, int $propertyId): JsonResponse
    {
        $user = $request->user();
        $exists = $user->favoriteProperties()->where('property_id', $propertyId)->exists();

        if ($exists) {
            $user->favoriteProperties()->detach($propertyId);
            $message = 'Property removed from wishlist.';
        } else {
            $user->favoriteProperties()->attach($propertyId);
            $message = 'Property added to wishlist.';
        }

        return response()->json(['message' => $message]);
    }
}
