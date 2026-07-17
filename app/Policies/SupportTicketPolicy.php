<?php

namespace App\Policies;

use App\Models\SupportTicket;
use App\Models\User;

class SupportTicketPolicy
{
    /**
     * Determine whether the user can view any support tickets.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the support ticket.
     */
    public function view(User $user, SupportTicket $ticket): bool
    {
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Content Manager']) || $user->role === 'admin') {
            return true;
        }

        return $user->id === $ticket->user_id;
    }

    /**
     * Determine whether the user can create support tickets.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update (or reply to) the support ticket.
     */
    public function update(User $user, SupportTicket $ticket): bool
    {
        if ($user->hasAnyRole(['Super Admin', 'Admin', 'Content Manager']) || $user->role === 'admin') {
            return true;
        }

        return $user->id === $ticket->user_id;
    }

    /**
     * Determine whether the user can delete the support ticket.
     */
    public function delete(User $user, SupportTicket $ticket): bool
    {
        return $user->hasAnyRole(['Super Admin', 'Admin']) || $user->role === 'admin';
    }
}
