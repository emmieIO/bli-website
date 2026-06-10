<?php

namespace App\Services\Event;

use App\Enums\EventRegistrationStatus;
use App\Events\EventRegisterEvent;
use App\Models\Event;
use App\Models\EventTransitionAudit;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class EventRegistrationService
{
    public function __construct(
        protected EventParticipantStateService $participantStateService
    ) {}

    public function registerForEvent(int $eventId, ?int $userId = null): bool
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            return false;
        }

        $event = Event::findOrFail($eventId);

        return $this->setRegistrationStatus($event, $userId, EventRegistrationStatus::REGISTERED) === EventRegistrationStatus::REGISTERED;
    }

    public function registerOrWaitlist(Event $event, ?int $userId = null): EventRegistrationStatus|false
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            return false;
        }

        $targetStatus = $this->participantStateService->slotsRemaining($event) === 'Full'
            ? EventRegistrationStatus::WAITLISTED
            : EventRegistrationStatus::REGISTERED;

        return $this->setRegistrationStatus($event, $userId, $targetStatus);
    }

    public function getEventsImAttending()
    {
        $user = Auth::user();

        if (! $user) {
            return collect([]);
        }

        return $user->events()
            ->wherePivotIn('status', EventRegistrationStatus::workspaceAccessibleValues())
            ->with(['resources', 'transactions' => fn ($query) => $query->where('user_id', $user->id)->latest()])
            ->get();
    }

    public function getAttendeeEventWorkspace(string $slug): ?Event
    {
        $user = Auth::user();

        if (! $user) {
            return null;
        }

        return $user->events()
            ->with([
                'resources',
                'speakers.user',
                'transactions' => fn ($query) => $query->where('user_id', $user->id)->latest(),
            ])
            ->where('events.slug', $slug)
            ->wherePivotIn('status', EventRegistrationStatus::workspaceAccessibleValues())
            ->first();
    }

    public function revokeRsvp(string $slug): bool
    {
        $event = Event::findBySlug($slug)->firstOrFail();
        $userId = Auth::id();

        if (! $userId) {
            return false;
        }

        try {
            return DB::transaction(function () use ($event, $userId) {
                $registration = $event->attendees()->where('user_id', $userId)->first();

                if (! $registration) {
                    return false;
                }

                $currentStatus = EventRegistrationStatus::fromValue($registration->pivot->status);

                if (! $currentStatus?->canCancel()) {
                    return false;
                }

                $releasedSeat = $currentStatus->occupiesSeat();

                $event->attendees()->updateExistingPivot($userId, [
                    'status' => EventRegistrationStatus::CANCELLED->value,
                    'revoke_count' => DB::raw('revoke_count + 1'),
                    'updated_at' => now(),
                ]);

                $this->recordRegistrationAudit(
                    event: $event,
                    userId: $userId,
                    action: 'registration_cancelled',
                    fromStatus: $currentStatus,
                    toStatus: EventRegistrationStatus::CANCELLED,
                    actorUserId: $userId,
                    context: [
                        'revoke_count' => (int) (($registration->pivot->revoke_count ?? 0) + 1),
                    ],
                );

                if ($releasedSeat) {
                    $this->promoteOldestWaitlistedAttendee($event, $userId);
                }

                return true;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to revoke RSVP.', [
                'event_id' => $event->id,
                'user_id' => $userId,
                'exception' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    public function promoteWaitlistedAttendee(Event $event, int $userId): bool|string
    {
        try {
            return DB::transaction(function () use ($event, $userId) {
                $event = Event::query()->findOrFail($event->id);
                $registration = $event->attendees()->where('user_id', $userId)->first();

                if (! $registration) {
                    return 'not_found';
                }

                $currentStatus = EventRegistrationStatus::fromValue($registration->pivot->status);

                if ($currentStatus !== EventRegistrationStatus::WAITLISTED) {
                    return 'invalid_status';
                }

                if ($this->participantStateService->slotsRemaining($event) === 'Full') {
                    return 'no_capacity';
                }

                return $this->promoteSpecificWaitlistedAttendee(
                    event: $event,
                    userId: $userId,
                    actorUserId: Auth::id(),
                    context: [
                        'trigger' => 'manual_promotion',
                    ],
                ) ? true : false;
            });
        } catch (Throwable $exception) {
            Log::error('Failed to promote waitlisted attendee.', [
                'event_id' => $event->id,
                'user_id' => $userId,
                'exception' => $exception->getMessage(),
            ]);

            return false;
        }
    }

    protected function promoteOldestWaitlistedAttendee(Event $event, ?int $actorUserId = null): bool
    {
        $nextWaitlistedAttendee = $event->attendees()
            ->wherePivot('status', EventRegistrationStatus::WAITLISTED->value)
            ->orderByPivot('created_at', 'asc')
            ->first();

        if (! $nextWaitlistedAttendee) {
            return false;
        }

        return $this->promoteSpecificWaitlistedAttendee(
            event: $event,
            userId: $nextWaitlistedAttendee->id,
            actorUserId: $actorUserId,
            context: [
                'trigger' => 'seat_released',
            ],
        );
    }

    protected function promoteSpecificWaitlistedAttendee(Event $event, int $userId, ?int $actorUserId = null, array $context = []): bool
    {
        $event->attendees()->updateExistingPivot($userId, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'updated_at' => now(),
        ]);

        $this->recordRegistrationAudit(
            event: $event,
            userId: $userId,
            action: 'waitlist_promoted',
            fromStatus: EventRegistrationStatus::WAITLISTED,
            toStatus: EventRegistrationStatus::REGISTERED,
            actorUserId: $actorUserId,
            context: $context,
        );

        $user = User::query()->find($userId, ['*']);

        if ($user) {
            event(new EventRegisterEvent($event, $user, 'promoted_from_waitlist'));
        }

        return true;
    }

    protected function setRegistrationStatus(Event $event, int $userId, EventRegistrationStatus $targetStatus): EventRegistrationStatus|false
    {
        $existing = $event->attendees()->where('user_id', $userId)->first();

        if ($existing && $existing->pivot->status === $targetStatus->value) {
            return $targetStatus;
        }

        if ($existing) {
            $currentStatus = EventRegistrationStatus::fromValue($existing->pivot->status);

            if (! $currentStatus) {
                Log::warning("User {$userId} has an unknown registration state for event {$event->id}. Registration denied.");

                return false;
            }

            if (! $currentStatus->canTransitionTo($targetStatus)) {
                Log::warning("User {$userId} attempted invalid registration transition for event {$event->id}.", [
                    'from' => $currentStatus->value,
                    'to' => $targetStatus->value,
                ]);

                return false;
            }

            if ($currentStatus === EventRegistrationStatus::CANCELLED && ($existing->pivot->revoke_count ?? 0) >= 4) {
                Log::warning("User {$userId} attempted to re-register for event {$event->id} but has reached the maximum revoke count ({$existing->pivot->revoke_count}). Registration denied.");

                return false;
            }

            $event->attendees()->updateExistingPivot($userId, [
                'status' => $targetStatus->value,
                'updated_at' => now(),
            ]);

            $this->recordRegistrationAudit(
                event: $event,
                userId: $userId,
                action: $targetStatus === EventRegistrationStatus::WAITLISTED ? 'registration_waitlisted' : 'registration_confirmed',
                fromStatus: $currentStatus,
                toStatus: $targetStatus,
                actorUserId: Auth::id(),
            );

            if ($targetStatus === EventRegistrationStatus::REGISTERED && $currentStatus !== EventRegistrationStatus::REGISTERED) {
                $user = User::query()->find($userId, ['*']);

                if ($user) {
                    event(new EventRegisterEvent($event, $user, 'confirmed'));
                }
            }

            return $targetStatus;
        }

        $event->attendees()->attach($userId, [
            'status' => $targetStatus->value,
            'revoke_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->recordRegistrationAudit(
            event: $event,
            userId: $userId,
            action: $targetStatus === EventRegistrationStatus::WAITLISTED ? 'registration_waitlisted' : 'registration_confirmed',
            fromStatus: null,
            toStatus: $targetStatus,
            actorUserId: Auth::id(),
        );

        if ($targetStatus === EventRegistrationStatus::REGISTERED) {
            $user = User::query()->find($userId, ['*']);

            if ($user) {
                event(new EventRegisterEvent($event, $user, 'confirmed'));
            }
        }

        return $targetStatus;
    }

    protected function recordRegistrationAudit(
        Event $event,
        int $userId,
        string $action,
        ?EventRegistrationStatus $fromStatus,
        ?EventRegistrationStatus $toStatus,
        ?int $actorUserId = null,
        array $context = [],
    ): void {
        EventTransitionAudit::query()->create([
            'event_id' => $event->id,
            'user_id' => $userId,
            'actor_user_id' => $actorUserId,
            'action' => $action,
            'from_status' => $fromStatus?->value,
            'to_status' => $toStatus?->value,
            'context' => $context === [] ? null : $context,
        ]);
    }
}
