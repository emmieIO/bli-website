<?php

namespace App\Http\Controllers\UserDashBoard;

use App\Http\Controllers\Controller;
use App\Services\Event\EventRegistrationService;
use Illuminate\Http\Request;

class ShowMyEventsController extends Controller
{
    public function __construct(
        protected EventRegistrationService $eventRegistrationService
    ) {
    }
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $events = $this->eventRegistrationService->getEventsImAttending()->map(function ($event) {
            $event->journey_status = now()->isBefore($event->start_date)
                ? 'upcoming'
                : (now()->isAfter($event->end_date) ? 'ended' : 'ongoing');
            $event->registration_status = $event->pivot?->status;
            $event->latest_transaction = $event->transactions->first();

            return $event;
        });
        return \Inertia\Inertia::render("MyEvents/Index", compact("events"));
    }
}
