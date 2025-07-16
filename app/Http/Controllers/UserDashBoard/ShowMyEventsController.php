<?php

namespace App\Http\Controllers\UserDashboard;

use App\Http\Controllers\Controller;
use App\Services\Event\EventService;
use Illuminate\Http\Request;

class ShowMyEventsController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ){}
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $events = $this->eventService->getEventsImAttending()->map(function ($event) {

            $event->status = now()->isBefore($event->start_date)
                ? 'upcoming'
                : (now()->isAfter($event->end_date) ? 'ended' : 'ongoing');


            $event->start_date = sweet_date($event->start_date);
            $event->end_date = sweet_date($event->end_date);

            return $event;
        });
        return view("user_dashboard.my-events", compact("events"));
    }
}
