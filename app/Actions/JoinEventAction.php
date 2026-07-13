<?php

namespace App\Actions;

use App\Enums\EventRegistrationStatus;
use App\Models\Event;
use App\Services\Event\EventParticipantStateService;
use App\Services\Event\EventRegistrationService;
use Illuminate\Http\Request;

class JoinEventAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected EventRegistrationService $eventRegistrationService,
        protected EventParticipantStateService $participantStateService
    ) {}

    /**
     * Invoke the class instance.
     */
    public function __invoke(Request $request, $slug)
    {

        $event = Event::findBySlug($slug)->firstOrFail();
        $workspaceRoute = route('user.events.show', $event->slug);

        // Check if user is already registered
        $userId = auth()->id();
        $isGuestRegistration = ! $userId;

        if ($isGuestRegistration) {
            return $this->handleGuestRegistration($request, $event);
        }

        $existing = $event->attendees()->where('user_id', $userId)->first();

        if ($existing && $existing->pivot->status === EventRegistrationStatus::REGISTERED->value) {
            return redirect($workspaceRoute)->with([
                'type' => 'info',
                'message' => 'Your seat is already confirmed for this event.',
            ]);
        }

        if ($existing && $existing->pivot->status === EventRegistrationStatus::WAITLISTED->value) {
            return redirect($workspaceRoute)->with([
                'type' => 'info',
                'message' => 'You are already on the waitlist for this event.',
            ]);
        }

        if (! $event->isRegistrationOpen()) {
            return back()->with([
                'type' => 'error',
                'message' => 'Registration is not open for this event.',
            ]);
        }

        // Check if event has expired
        if (isset($event->end_date) && now()->greaterThan($event->end_date)) {
            return back()->with([
                'type' => 'error',
                'message' => 'Registration failed. The event has already ended.',
            ]);
        }

        // Check if event has started
        // if (isset($event->start_date) && now()->greaterThanOrEqualTo($event->start_date)) {
        //     return back()->with([
        //         "type" => "error",
        //         "message" => "Registration failed. The event has already started."
        //     ]);
        // }

        // Check for maximum registrations
        $maxRevokes = $this->participantStateService->hasReachedMaxRevokes($event, $userId);
        if (isset($maxRevokes) && $maxRevokes) {
            return back()->with([
                'type' => 'error',
                'message' => 'Registration failed. The event has reached its maximum number of registrations.',
            ]);
        }

        // Check if event is paid
        if ($event->entry_fee > 0) {
            if ($this->participantStateService->slotsRemaining($event) === 'Full') {
                $result = $this->eventRegistrationService->registerOrWaitlist($event, $userId);

                if ($result === EventRegistrationStatus::WAITLISTED) {
                    return redirect($workspaceRoute)->with([
                        'type' => 'success',
                        'message' => 'This event is currently full. You have been added to the waitlist.',
                    ]);
                }
            }

            return redirect()->route('events.checkout', $event->slug);
        }

        $result = $this->eventRegistrationService->registerOrWaitlist($event, $userId);

        if ($result === EventRegistrationStatus::REGISTERED) {
            return redirect($workspaceRoute)->with([
                'type' => 'success',
                'message' => 'Your event registration has been confirmed.',
            ]);
        }

        if ($result === EventRegistrationStatus::WAITLISTED) {
            return redirect($workspaceRoute)->with([
                'type' => 'success',
                'message' => 'This event is full right now. You have been added to the waitlist.',
            ]);
        }

        return back()->with([
            'type' => 'error',
            'message' => 'Registration failed. Please try again later or contact support.',
        ]);

    }

    private function handleGuestRegistration(Request $request, Event $event)
    {
        if ($event->require_sign_up) {
            return redirect()->route('login')->with([
                'type' => 'info',
                'message' => 'Please sign in to register for this event.',
            ]);
        }

        if ((float) $event->entry_fee > 0) {
            return redirect()->route('login')->with([
                'type' => 'info',
                'message' => 'Please sign in to register for paid events.',
            ]);
        }

        if (! $event->isRegistrationOpen()) {
            return back()->with([
                'type' => 'error',
                'message' => 'Registration is not open for this event.',
            ]);
        }

        if (isset($event->end_date) && now()->greaterThan($event->end_date)) {
            return back()->with([
                'type' => 'error',
                'message' => 'Registration failed. The event has already ended.',
            ]);
        }

        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
        ]);

        $existing = $event->guestAttendees()
            ->where('email', mb_strtolower(trim($validated['email'])))
            ->first();

        if ($existing && $existing->status === EventRegistrationStatus::REGISTERED) {
            return back()->with([
                'type' => 'info',
                'message' => 'This email is already confirmed for this event.',
            ]);
        }

        if ($existing && $existing->status === EventRegistrationStatus::WAITLISTED) {
            return back()->with([
                'type' => 'info',
                'message' => 'This email is already on the waitlist for this event.',
            ]);
        }

        $result = $this->eventRegistrationService->registerGuestOrWaitlist(
            $event,
            $validated['email'],
            $validated['name'] ?? null
        );

        if ($result === EventRegistrationStatus::REGISTERED) {
            return back()->with([
                'type' => 'success',
                'message' => 'Your email has been added to the attendee list. We will send event reminders to that address.',
            ]);
        }

        if ($result === EventRegistrationStatus::WAITLISTED) {
            return back()->with([
                'type' => 'success',
                'message' => 'This event is full right now. Your email has been added to the waitlist.',
            ]);
        }

        return back()->with([
            'type' => 'error',
            'message' => 'Registration failed. Please try again later or contact support.',
        ]);
    }
}
