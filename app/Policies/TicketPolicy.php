<?php

namespace App\Policies;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class TicketPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Users can view their own tickets
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Ticket $ticket): bool
    {
        // Users can view their own tickets, admins can view all tickets
        return $user->id === $ticket->user_id || $user->hasRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // All authenticated users can create tickets
        return true;
    }

    /**
     * Determine whether the user can reply to the ticket.
     */
    public function reply(User $user, Ticket $ticket): bool
    {
        // Users can reply to their own tickets, admins can reply to any ticket
        return $user->id === $ticket->user_id || $user->hasRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can update the ticket status.
     */
    public function updateStatus(User $user, Ticket $ticket): bool
    {
        // Only admins can update ticket status
        return $user->hasRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Ticket $ticket): bool
    {
        // Only admins can update tickets
        return $user->hasRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Ticket $ticket): bool
    {
        // Only admins can delete tickets
        return $user->hasRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Ticket $ticket): bool
    {
        return $user->hasRole(['admin', 'super-admin']);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Ticket $ticket): bool
    {
        return $user->hasRole(['admin', 'super-admin']);
    }
}
