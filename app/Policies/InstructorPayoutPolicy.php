<?php

namespace App\Policies;

use App\Models\InstructorPayout;
use App\Models\User;

class InstructorPayoutPolicy
{
    /**
     * Determine if the user can view any payouts
     */
    public function viewAny(User $user): bool
    {
        // Only admins with earnings-manage-any permission can view all payouts
        return $user->hasPermissionTo('earnings-manage-any');
    }

    /**
     * Determine if the user can view a specific payout
     */
    public function view(User $user, InstructorPayout $payout): bool
    {
        // Admins can view any payout
        if ($user->hasPermissionTo('earnings-manage-any')) {
            return true;
        }

        // Instructors can only view their own payouts
        return $user->id === $payout->instructor_id && $user->hasPermissionTo('earnings-view-own');
    }

    /**
     * Determine if the user can create a payout request
     */
    public function create(User $user): bool
    {
        // Only instructors with earnings-view-own permission
        return $user->hasPermissionTo('earnings-view-own');
    }

    /**
     * Determine if the user can update a payout
     * (Only admins can update status, instructors cannot edit after submission)
     */
    public function update(User $user, InstructorPayout $payout): bool
    {
        // Only admins can update payouts
        return $user->hasPermissionTo('earnings-manage-any');
    }

    /**
     * Determine if the user can delete/cancel a payout
     */
    public function delete(User $user, InstructorPayout $payout): bool
    {
        // Admins can cancel any pending payout
        if ($user->hasPermissionTo('earnings-manage-any')) {
            return $payout->status === 'pending';
        }

        // Instructors can only cancel their own pending payouts
        return $user->id === $payout->instructor_id &&
               $payout->status === 'pending' &&
               $user->hasPermissionTo('earnings-view-own');
    }

    /**
     * Determine if the user can mark payout as processing
     */
    public function markAsProcessing(User $user, InstructorPayout $payout): bool
    {
        return $user->hasPermissionTo('earnings-manage-any') && $payout->status === 'pending';
    }

    /**
     * Determine if the user can mark payout as completed
     */
    public function markAsCompleted(User $user, InstructorPayout $payout): bool
    {
        return $user->hasPermissionTo('earnings-manage-any') &&
               in_array($payout->status, ['pending', 'processing']);
    }

    /**
     * Determine if the user can mark payout as failed
     */
    public function markAsFailed(User $user, InstructorPayout $payout): bool
    {
        return $user->hasPermissionTo('earnings-manage-any') &&
               in_array($payout->status, ['pending', 'processing']);
    }
}
