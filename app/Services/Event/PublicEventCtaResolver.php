<?php

namespace App\Services\Event;

use App\Enums\EventStatus;
use App\Enums\SpeakerWorkspaceStage;
use App\Models\Event;
use App\Models\SpeakerApplication;
use App\Models\SpeakerInvite;
use App\Models\User;
use App\Services\Speakers\SpeakerTransitionService;
use Illuminate\Support\Facades\URL;

class PublicEventCtaResolver
{
    public function __construct(
        protected SpeakerTransitionService $speakerTransitionService,
        protected EventParticipantStateService $participantStateService
    ) {}

    public function resolve(Event $event, ?User $user = null): array
    {
        $speakerContext = $user ? $this->speakerWorkspaceContext($event, $user) : null;

        if ($event->lifecycleStatus() === EventStatus::CANCELLED) {
            return $this->status('cancelled', 'Event cancelled', 'This event is no longer accepting participation.');
        }

        if ($event->lifecycleStatus() === EventStatus::ARCHIVED) {
            return $this->status('archived', 'Event archived', 'This event has been archived and is no longer active.');
        }

        if (
            $event->lifecycleStatus() === EventStatus::COMPLETED
            || ($event->end_date !== null && now()->gt($event->end_date))
        ) {
            return $this->status('completed', 'Event completed', 'This event has already concluded.');
        }

        if ($user && $this->participantStateService->userHasAttendeeWorkspace($event, $user->id)) {
            return $this->action(
                'view_attendee_workspace',
                'Open attendee workspace',
                'Your registration is already on file. Use your attendee workspace for event access and updates.',
                route('user.events.show', $event->slug)
            );
        }

        if ($speakerContext !== null) {
            return $this->action(
                'view_speaker_workspace',
                'Open speaker workspace',
                'Continue your speaker journey from the dedicated workspace for this event.',
                route('speaker.events.show', $event->slug)
            );
        }

        if ($this->isLive($event)) {
            return $this->status('live', 'Event is live', 'Registration is no longer the primary action while this event is in progress.');
        }

        if (! $event->isRegistrationOpen()) {
            $speakerApplyUrl = $this->speakerApplyUrl($event, $user);

            if ($speakerApplyUrl !== null) {
                return $this->action(
                    'apply_to_speak',
                    'Apply to speak',
                    'Attendee registration is unavailable, but speaking applications are still open.',
                    $speakerApplyUrl
                );
            }

            return $this->status(
                'registration_closed',
                'Registration closed',
                'Registration is not currently available for this event.'
            );
        }

        if ($this->participantStateService->slotsRemaining($event) === 'Full') {
            return $this->joinAction(
                'join_waitlist',
                $event,
                $user,
                'Join waitlist',
                'This event is full right now, but you can still claim the next available seat.'
            );
        }

        if ((float) $event->entry_fee > 0) {
            if (! $user) {
                return $this->action(
                    'buy_ticket',
                    'Log in to buy ticket',
                    'Sign in to continue to ticket checkout.',
                    route('login'),
                    ['requires_auth' => true]
                );
            }

            return $this->action(
                'buy_ticket',
                'Buy ticket',
                'Continue to checkout to secure your place at this event.',
                route('events.checkout', $event->slug)
            );
        }

        return $this->joinAction(
            'register_now',
            $event,
            $user,
            'Register now',
            'Reserve your seat and move straight into the attendee journey.'
        );
    }

    private function joinAction(string $key, Event $event, ?User $user, string $label, string $description): array
    {
        if (! $user) {
            $guestLabel = $key === 'join_waitlist'
                ? 'Log in to join waitlist'
                : 'Log in to register';

            return $this->action($key, $guestLabel, $description, route('login'), [
                'requires_auth' => true,
            ]);
        }

        return $this->action($key, $label, $description, route('events.join', $event->slug), [
            'method' => 'post',
            'requires_confirmation' => true,
        ]);
    }

    private function speakerApplyUrl(Event $event, ?User $user = null): ?string
    {
        if (! $event->is_allowing_application || $event->lifecycleStatus() === EventStatus::CANCELLED) {
            return null;
        }

        if ($user) {
            return route('speaker.events.apply', $event->slug);
        }

        return URL::signedRoute('event.speakers.apply', [$event]);
    }

    private function speakerWorkspaceContext(Event $event, User $user): ?SpeakerWorkspaceStage
    {
        $speakerId = $user->speaker?->id;
        $application = SpeakerApplication::query()
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();
        $invite = $speakerId
            ? SpeakerInvite::query()
                ->where('event_id', $event->id)
                ->where('speaker_id', $speakerId)
                ->first()
            : null;
        $isAssignedSpeaker = $speakerId
            ? $event->speakers()->where('speakers.id', $speakerId)->exists()
            : false;

        return $this->speakerTransitionService->resolveWorkspaceStage($application, $invite, $isAssignedSpeaker);
    }

    private function isLive(Event $event): bool
    {
        if ($event->lifecycleStatus() === EventStatus::LIVE) {
            return true;
        }

        return $event->start_date !== null
            && $event->end_date !== null
            && now()->between($event->start_date, $event->end_date);
    }

    private function action(
        string $key,
        string $label,
        string $description,
        string $href,
        array $overrides = []
    ): array {
        return array_merge([
            'key' => $key,
            'kind' => 'action',
            'label' => $label,
            'description' => $description,
            'href' => $href,
            'method' => 'get',
            'requires_auth' => false,
            'requires_confirmation' => false,
        ], $overrides);
    }

    private function status(string $key, string $label, string $description): array
    {
        return [
            'key' => $key,
            'kind' => 'status',
            'label' => $label,
            'description' => $description,
            'href' => null,
            'method' => 'get',
            'requires_auth' => false,
            'requires_confirmation' => false,
        ];
    }
}
