<?php

namespace App\Services\Event;

use App\Enums\EventRegistrationStatus;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventParticipantStateService
{
    public function slotsRemaining(Event $event): int|string
    {
        if ($event->attendee_slots === null) {
            return 'Unlimited';
        }

        if ($event->attendee_slots === 0) {
            return 'Full';
        }

        $occupiedSeats = $event->attendees()
            ->wherePivotIn('status', EventRegistrationStatus::seatOccupyingValues())
            ->count();
        $guestOccupiedSeats = $event->guestAttendees()
            ->whereIn('status', EventRegistrationStatus::seatOccupyingValues())
            ->count();

        $remainingSeats = $event->attendee_slots - $occupiedSeats - $guestOccupiedSeats;

        return $remainingSeats <= 0 ? 'Full' : $remainingSeats;
    }

    public function normalizedSlotsRemaining(Event $event): ?int
    {
        $slotsRemaining = $this->slotsRemaining($event);

        if ($slotsRemaining === 'Unlimited') {
            return null;
        }

        return $slotsRemaining === 'Full' ? 0 : $slotsRemaining;
    }

    public function registrationStatusForUserEnum(Event $event, ?int $userId = null): ?EventRegistrationStatus
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            return null;
        }

        $attendee = $event->attendees()
            ->where('user_id', $userId)
            ->first();

        return EventRegistrationStatus::fromValue($attendee?->pivot?->status);
    }

    public function registrationStatusForUser(Event $event, ?int $userId = null): ?string
    {
        return $this->registrationStatusForUserEnum($event, $userId)?->value;
    }

    public function isRegistered(Event $event, ?int $userId = null): bool
    {
        return $this->registrationStatusForUserEnum($event, $userId) === EventRegistrationStatus::CONFIRMED;
    }

    public function userHasAttendeeWorkspace(Event $event, ?int $userId = null): bool
    {
        return $this->registrationStatusForUserEnum($event, $userId)?->hasAttendeeWorkspace() ?? false;
    }

    public function getRevokeCount(Event $event, ?int $userId = null): int
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            return 0;
        }

        $attendee = $event->attendees()
            ->where('user_id', $userId)
            ->first();

        return (int) ($attendee?->pivot?->revoke_count ?? 0);
    }

    public function hasReachedMaxRevokes(Event $event, ?int $userId = null): bool
    {
        return $this->getRevokeCount($event, $userId) >= 4;
    }

    public function canUserRegister(Event $event, ?int $userId = null): bool
    {
        if (! $event->isRegistrationOpen()) {
            return false;
        }

        $status = $this->registrationStatusForUserEnum($event, $userId);

        return $status === null || $status === EventRegistrationStatus::CANCELLED;
    }
}
