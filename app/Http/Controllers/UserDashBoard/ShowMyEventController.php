<?php

namespace App\Http\Controllers\UserDashBoard;

use App\Http\Controllers\Controller;
use App\Services\Event\EventService;
use Inertia\Inertia;

class ShowMyEventController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ) {
    }

    public function __invoke(string $slug)
    {
        $event = $this->eventService->getAttendeeEventWorkspace($slug);

        abort_unless($event, 404);

        $event->journey_status = now()->isBefore($event->start_date)
            ? 'upcoming'
            : (now()->isAfter($event->end_date) ? 'ended' : 'ongoing');

        $event->registration_status = $event->pivot?->status;
        $event->slots_remaining = $event->slotsRemaining() === 'Unlimited'
            ? null
            : ($event->slotsRemaining() === 'Full' ? 0 : $event->slotsRemaining());
        $event->meeting_link = data_get($event->metadata, 'meeting_link');
        $event->access_notes = data_get($event->metadata, 'access_notes');
        $event->latest_transaction = $event->transactions->first();
        $event->refund_request = $event->refundRequests->first();
        $event->program_profile = [
            'program_type' => data_get($event->metadata, 'program_type', 'general_event'),
            'program_code' => data_get($event->metadata, 'program_code'),
            'registration_mode' => data_get($event->metadata, 'registration_mode', 'open'),
            'requires_screening' => (bool) data_get($event->metadata, 'requires_screening', false),
            'screening_note' => data_get($event->metadata, 'screening_note'),
            'cohort_duration_weeks' => data_get($event->metadata, 'cohort_duration_weeks'),
            'group_model' => data_get($event->metadata, 'group_model'),
            'central_teaching_schedule' => data_get($event->metadata, 'central_teaching_schedule'),
            'group_meeting_schedule' => data_get($event->metadata, 'group_meeting_schedule'),
            'weekly_prayer_target_minutes' => data_get($event->metadata, 'weekly_prayer_target_minutes'),
            'weekly_evangelism_target_min' => data_get($event->metadata, 'weekly_evangelism_target_min'),
            'weekly_evangelism_target_max' => data_get($event->metadata, 'weekly_evangelism_target_max'),
            'weekly_discipleship_target_min' => data_get($event->metadata, 'weekly_discipleship_target_min'),
            'weekly_discipleship_target_max' => data_get($event->metadata, 'weekly_discipleship_target_max'),
        ];

        return Inertia::render('MyEvents/Show', [
            'event' => $event,
        ]);
    }
}
