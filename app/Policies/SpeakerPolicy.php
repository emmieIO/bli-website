<?php

namespace App\Policies;

use App\Models\Speaker;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SpeakerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('view-speaker');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Speaker $speaker): bool
    {
        return $user->hasPermissionTo('view-speakers');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('create-speaker');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Speaker $speaker): bool
    {
        return $user->hasPermissionTo('update-speaker');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Speaker $speaker): bool
    {
        return $user->hasPermissionTo('delete-speaker');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Speaker $speaker): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Speaker $speaker): bool
    {
        return false;
    }
}
