<?php

namespace App\Policies;

use App\Models\CrmLead;
use App\Models\User;

class CrmLeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    public function view(User $user, CrmLead $lead): bool
    {
        return $user->hasRole('admin') || $user->id === $lead->agent_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('agent');
    }

    public function update(User $user, CrmLead $lead): bool
    {
        return $user->hasRole('admin') || $user->id === $lead->agent_id;
    }

    public function delete(User $user, CrmLead $lead): bool
    {
        return $user->hasRole('admin') || $user->id === $lead->agent_id;
    }
}
