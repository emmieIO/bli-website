<?php

namespace App\Services\Event;

use App\Enums\EventRegistrationStatus;
use App\Events\EventRegisterEvent;
use App\Models\Event;
use App\Models\EventGuestAttendee;
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

    public function registerIfAvailable(Event $event, ?int $userId = null): EventRegistrationStatus|false
    {
        $userId = $userId ?? Auth::id();

        if (! $userId) {
            return false;
        }

        if ($this->participantStateService->slotsRemaining($event) === 'Full') {
            return false;
        }

        return $this->setRegistrationStatus($event, $userId, EventRegistrationStatus::REGISTERED);
    }

    public function registerGuestIfAvailable(Event $event, string $email, string $name): EventRegistrationStatus|false
    {
        $email = mb_strtolower(trim($email));
        $name = trim($name);

        if ($name === '' || $email === '' || ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        if ($this->participantStateService->slotsRemaining($event) === 'Full') {
            return false;
        }

        $targetStatus = EventRegistrationStatus::REGISTERED;

        $existing = $event->guestAttendees()
            ->where('email', $email)
            ->first();

        if ($existing && $existing->status === $targetStatus) {
            return $targetStatus;
        }

        if ($existing) {
            $currentStatus = $existing->status;

            if (! $currentStatus?->canTransitionTo($targetStatus)) {
                return false;
            }

            $existing->update([
                'name' => $name,
                'status' => $targetStatus->value,
            ]);

            return $targetStatus;
        }

        EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'email' => $email,
            'name' => $name,
            'status' => $targetStatus->value,
        ]);

        return $targetStatus;
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
                action: 'registration_confirmed',
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
            action: 'registration_confirmed',
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
