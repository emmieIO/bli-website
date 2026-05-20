<?php

namespace App\Http\Controllers\UserDashBoard;

use App\Http\Controllers\Controller;
use App\Services\Event\EventRegistrationService;
use Inertia\Inertia;

class ShowEventRefundPageController extends Controller
{
    public function __construct(
        protected EventRegistrationService $eventRegistrationService
    ) {}

    public function __invoke(string $slug)
    {
        $event = $this->eventRegistrationService->getAttendeeEventWorkspace($slug);

        abort_unless($event, 404);

        $event->journey_status = now()->isBefore($event->start_date)
            ? 'upcoming'
            : (now()->isAfter($event->end_date) ? 'ended' : 'ongoing');

        $event->registration_status = $event->pivot?->status;
        $event->meeting_link = data_get($event->metadata, 'meeting_link');
        $event->access_notes = data_get($event->metadata, 'access_notes');
        $event->latest_transaction = $event->transactions->first();
        $event->refund_request = $event->refundRequests->first();

        abort_unless($event->latest_transaction, 404);

        return Inertia::render('MyEvents/Refund', [
            'event' => $event,
        ]);
    }
}
