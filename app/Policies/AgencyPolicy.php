<?php

namespace App\Policies;

use App\Models\Agency;
use App\Models\User;

class AgencyPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Agency $agency): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    public function update(User $user, Agency $agency): bool
    {
        return $user->hasRole('admin') || $user->id === $agency->owner_id;
    }

    public function delete(User $user, Agency $agency): bool
    {
        return $user->hasRole('admin') || $user->id === $agency->owner_id;
    }
}
