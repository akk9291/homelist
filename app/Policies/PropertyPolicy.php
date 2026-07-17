<?php

namespace App\Policies;

use App\Models\Property;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PropertyPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(?User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Property $property): bool
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('Agent') || $user->role === 'agent' || $user->hasRole('Admin') || $user->role === 'admin';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Property $property): bool
    {
        // Agent can update only their own properties
        if ($user->hasRole('Agent') || $user->role === 'agent') {
            return $user->id === $property->agent_id;
        }

        // Admin can update any property
        return $user->hasAnyRole(['Super Admin', 'Admin', 'Content Manager']) || $user->role === 'admin';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Property $property): bool
    {
        // Agent can delete only their own properties
        if ($user->hasRole('Agent') || $user->role === 'agent') {
            return $user->id === $property->agent_id;
        }

        // Admin can delete any property
        return $user->hasAnyRole(['Super Admin', 'Admin']) || $user->role === 'admin';
    }
}
