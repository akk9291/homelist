<?php

namespace App\Policies;

use App\Models\UserSubscription;
use App\Models\User;

class UserSubscriptionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin');
    }

    public function view(User $user, UserSubscription $sub): bool
    {
        return $user->hasRole('admin') || $user->id === $sub->user_id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, UserSubscription $sub): bool
    {
        return $user->hasRole('admin');
    }

    public function delete(User $user, UserSubscription $sub): bool
    {
        return $user->hasRole('admin');
    }
}
