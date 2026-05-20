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
    ) {
    }
    /**
     * Invoke the class instance.
     */
    public function __invoke(Request $request, $slug)
    {

        $event = Event::findBySlug($slug)->firstOrFail();
        $workspaceRoute = route('user.events.show', $event->slug);

        // Check if user is already registered
        $userId = auth()->id();
        $existing = $event->attendees()->where('user_id', $userId)->first();

        if ($existing && $existing->pivot->status === EventRegistrationStatus::REGISTERED->value) {
            return redirect($workspaceRoute)->with([
                "type" => "info",
                "message" => "Your seat is already confirmed for this event."
            ]);
        }

        if ($existing && $existing->pivot->status === EventRegistrationStatus::WAITLISTED->value) {
            return redirect($workspaceRoute)->with([
                "type" => "info",
                "message" => "You are already on the waitlist for this event."
            ]);
        }

        if (! $event->isRegistrationOpen()) {
            return back()->with([
                "type" => "error",
                "message" => "Registration is not open for this event."
            ]);
        }

        // Check if event has expired
        if (isset($event->end_date) && now()->greaterThan($event->end_date)) {
            return back()->with([
                "type" => "error",
                "message" => "Registration failed. The event has already ended."
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
                "type" => "error",
                "message" => "Registration failed. The event has reached its maximum number of registrations."
            ]);
        }

        // Check if event is paid
        if ($event->entry_fee > 0) {
            if ($this->participantStateService->slotsRemaining($event) === 'Full') {
                $result = $this->eventRegistrationService->registerOrWaitlist($event, $userId);

                if ($result === EventRegistrationStatus::WAITLISTED) {
                    return redirect($workspaceRoute)->with([
                        "type" => "success",
                        "message" => "This event is currently full. You have been added to the waitlist."
                    ]);
                }
            }

            return redirect()->route('events.checkout', $event->slug);
        }

        $result = $this->eventRegistrationService->registerOrWaitlist($event, $userId);

        if ($result === EventRegistrationStatus::REGISTERED) {
            return redirect($workspaceRoute)->with([
                "type" => "success",
                "message" => "Your event registration has been confirmed."
            ]);
        }

        if ($result === EventRegistrationStatus::WAITLISTED) {
            return redirect($workspaceRoute)->with([
                "type" => "success",
                "message" => "This event is full right now. You have been added to the waitlist."
            ]);
        }
        return back()->with([
            "type" => "error",
            "message" => "Registration failed. Please try again later or contact support."
        ]);


    }
}
