<?php

namespace App\Actions;

use App\Models\Event;
use App\Services\Event\EventService;
use Illuminate\Http\Request;

class JoinEventAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected EventService $eventService
    ) {
    }
    /**
     * Invoke the class instance.
     */
    public function __invoke(Request $request, $slug)
    {

        $event = Event::findBySlug($slug)->firstOrFail();

        // Check if user is already registered
        $userId = auth()->id();
        $existing = $event->attendees()->where('user_id', $userId)->first();

        if ($existing && $existing->pivot->status === 'registered') {
            return back()->with([
                "type" => "info",
                "message" => "You are already registered for this event."
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


        if ($event->attendee_slots !== null) {
            // Check for available slots
            $slotsRemaining = $event->slotsRemaining();
            if ($slotsRemaining <= 0) {
                return back()->with([
                    "type" => "error",
                    "message" => "Registration failed. No available slots remaining for this event."
                ]);
            }
        }

        // Check for maximum registrations
        $maxRevokes = $event->maxRevokes();
        if (isset($maxRevokes) && $maxRevokes) {
            return back()->with([
                "type" => "error",
                "message" => "Registration failed. The event has reached its maximum number of registrations."
            ]);
        }

        if ($this->eventService->registerForEvent($event->id)) {
            return back()->with([
                "type" => "success",
                "message" => "You have successfully registered for the event."
            ]);
        }
        return back()->with([
            "type" => "error",
            "message" => "Registration failed. Please try again later or contact support."
        ]);


    }
}
