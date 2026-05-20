<?php

namespace App\Policies;

use App\Enums\EventStatus;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Models\Event;
use App\Models\User;
use App\Services\Event\EventParticipantStateService;
use Illuminate\Auth\Access\Response;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models (Admin only).
     */
    public function viewAny(?User $user): bool
    {
        if (!$user) {
            return false;
        }

        return $user->hasPermissionTo(EventPermissionsEnum::VIEW_ANY->value);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Event $event): bool
    {
        if (!$user) {
            return $event->isPubliclyVisible();
        }

        // Admin can view any event
        if ($user->hasPermissionTo(EventPermissionsEnum::VIEW_ANY->value)) {
            return true;
        }

        // Authenticated users can view non-canceled events
        return $user->hasPermissionTo(EventPermissionsEnum::VIEW->value)
            && $event->isPubliclyVisible();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::CREATE->value);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::UPDATE_ANY->value);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // Only admin can delete events
        return $user->hasPermissionTo(EventPermissionsEnum::DELETE_ANY->value);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::DELETE_ANY->value);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::DELETE_ANY->value);
    }

    /**
     * Determine whether the user can register for an event.
     */
    public function register(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::REGISTER->value)
            && app(EventParticipantStateService::class)->canUserRegister($event, $user->id);
    }

    /**
     * Determine whether the user can apply to speak at an event.
     */
    public function applyToSpeak(User $user, Event $event): bool
    {
        $canApply = $user->hasPermissionTo(EventPermissionsEnum::APPLY_TO_SPEAK->value)
            || $user->hasPermissionTo(EventPermissionsEnum::MANAGE_SPEAKERS->value)
            || $user->hasPermissionTo(EventPermissionsEnum::VIEW_ANY->value);

        return $canApply
            && $event->is_allowing_application
            && $event->lifecycleStatus() !== EventStatus::CANCELLED;
    }

    /**
     * Determine whether the user can manage event attendees.
     */
    public function manageAttendees(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::MANAGE_ATTENDEES->value);
    }

    /**
     * Determine whether the user can manage the waitlist for an event.
     */
    public function manageWaitlist(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::MANAGE_WAITLIST->value)
            || $user->hasPermissionTo(EventPermissionsEnum::MANAGE_ATTENDEES->value);
    }

    /**
     * Determine whether the user can manage event speakers.
     */
    public function manageSpeakers(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::MANAGE_SPEAKERS->value);
    }

    /**
     * Determine whether the user can manage event resources.
     */
    public function manageResources(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::MANAGE_RESOURCES->value);
    }

    /**
     * Determine whether the user can view event payments.
     */
    public function viewPayments(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::VIEW_PAYMENTS->value);
    }

    /**
     * Determine whether the user can send event updates.
     */
    public function sendUpdates(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::SEND_UPDATES->value);
    }

    /**
     * Determine whether the user can publish an event.
     */
    public function publish(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::PUBLISH->value);
    }

    /**
     * Determine whether the user can cancel an event.
     */
    public function cancel(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::CANCEL->value)
            && $event->lifecycleStatus() !== EventStatus::CANCELLED;
    }

    /**
     * Determine whether the user can archive an event.
     */
    public function archive(User $user, Event $event): bool
    {
        return $user->hasPermissionTo(EventPermissionsEnum::ARCHIVE->value)
            && $event->lifecycleStatus() !== EventStatus::ARCHIVED;
    }
}
