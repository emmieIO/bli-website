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
        return match (true) {
            $event->lifecycleStatus() === EventStatus::CANCELLED => $this->status('cancelled', 'Event cancelled', 'This event is no longer accepting participation.'),
            $event->lifecycleStatus() === EventStatus::ARCHIVED => $this->status('archived', 'Event archived', 'This event has been archived and is no longer active.'),
            $this->isFinished($event) => $this->status('completed', 'Event completed', 'This event has already concluded.'),
            $user && $this->participantStateService->userHasAttendeeWorkspace($event, $user->id) => $this->action('view_attendee_workspace', 'Open attendee workspace', 'Your registration is already on file.', route('user.events.show', $event->slug)),
            $user && $this->speakerContext($event, $user) !== null => $this->action('view_speaker_workspace', 'Open speaker workspace', 'Continue your speaker journey.', route('speaker.events.show', $event->slug)),
            $this->isLive($event) => $this->status('live', 'Event is live', 'Registration is not the primary action while the event is in progress.'),
            ! $event->isRegistrationOpen() => $this->resolveClosedRegistration($event, $user),
            $this->participantStateService->slotsRemaining($event) === 'Full' => $this->joinAction('join_waitlist', $event, $user, 'Join waitlist', 'This event is full, but you can claim the next available seat.'),
            (float) $event->entry_fee > 0 => $this->resolvePaidEvent($event, $user),
            default => $this->joinAction('register_now', $event, $user, 'Register now', 'Reserve your seat and move into the attendee journey.'),
        };
    }

    private function resolveClosedRegistration(Event $event, ?User $user): array
    {
        $speakerUrl = $this->speakerApplyUrl($event, $user);

        return $speakerUrl
            ? $this->action('apply_to_speak', 'Apply to speak', 'Attendee registration is unavailable, but speaking applications are still open.', $speakerUrl)
            : $this->status('registration_closed', 'Registration closed', 'Registration is not currently available for this event.');
    }

    private function resolvePaidEvent(Event $event, ?User $user): array
    {
        return $user
            ? $this->action('buy_ticket', 'Buy ticket', 'Continue to checkout.', route('events.checkout', $event->slug))
            : $this->action('buy_ticket', 'Log in to buy ticket', 'Sign in to continue to checkout.', route('login'), ['requires_auth' => true]);
    }

    private function joinAction(string $key, Event $event, ?User $user, string $label, string $description): array
    {
        if (! $user) {
            if (! $event->require_sign_up && (float) $event->entry_fee <= 0) {
                $guestLabel = $key === 'join_waitlist' ? 'Join waitlist with email' : 'Register with email';

                return $this->action($key, $guestLabel, 'No account needed. Use your email to receive event reminders.', route('events.join', $event->slug), [
                    'method' => 'post',
                    'requires_confirmation' => true,
                    'requires_email' => true,
                ]);
            }

            $guestLabel = $key === 'join_waitlist' ? 'Log in to join waitlist' : 'Log in to register';

            return $this->action($key, $guestLabel, $description, route('login'), ['requires_auth' => true]);
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

        return $user
            ? route('speaker.events.apply', $event->slug)
            : URL::signedRoute('event.speakers.apply', [$event]);
    }

    private function speakerContext(Event $event, User $user): ?SpeakerWorkspaceStage
    {
        $speakerId = $user->speaker?->id;
        $application = SpeakerApplication::query()->where('event_id', $event->id)->where('user_id', $user->id)->first();
        $invite = $speakerId ? SpeakerInvite::query()->where('event_id', $event->id)->where('speaker_id', $speakerId)->first() : null;
        $isAssignedSpeaker = $speakerId && $event->speakers()->where('speakers.id', $speakerId)->exists();

        return $this->speakerTransitionService->resolveWorkspaceStage($application, $invite, $isAssignedSpeaker);
    }

    private function isLive(Event $event): bool
    {
        return $event->lifecycleStatus() === EventStatus::LIVE
            || ($event->start_date && $event->end_date && now()->between($event->start_date, $event->end_date));
    }

    private function isFinished(Event $event): bool
    {
        return $event->lifecycleStatus() === EventStatus::COMPLETED
            || ($event->end_date && now()->gt($event->end_date));
    }

    private function action(string $key, string $label, string $description, string $href, array $overrides = []): array
    {
        return array_merge([
            'key' => $key,
            'kind' => 'action',
            'label' => $label,
            'description' => $description,
            'href' => $href,
            'method' => 'get',
            'requires_auth' => false,
            'requires_confirmation' => false,
            'requires_email' => false,
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
            'requires_email' => false,
        ];
    }
}
