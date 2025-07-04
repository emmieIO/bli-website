<?php

namespace App\Actions;

use App\Models\Programme;
use App\Services\Event\EventService;
use Illuminate\Http\Request;

class JoinEventAction
{
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected EventService $eventService
    )
    {
        //
    }

    /**
     * Invoke the class instance.
     */
    public function __invoke(Request $request, $slug)
    {

        $event = Programme::findBySlug($slug)->firstOrFail();
        $this->eventService->registerForEvent($event->id);

        // Log the event registration for auditing purposes
        logger()->info('User registered for event', [
            'user_id' => $request->user()->id,
            'event_id' => $event->id,
            'timestamp' => now(),
        ]);

        return back()->with([
            "type" => "success",
            "message" => "You have successfully registered for the event."
        ]);
    }
}
