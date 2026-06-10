<?php

namespace App\Http\Controllers;

use App\Contracts\ProgramRepositoryInterface;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Services\Event\EventParticipantStateService;
use App\Services\Event\EventQueryService;
use App\Services\Event\PublicEventCtaResolver;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function __construct(
        protected ProgramRepositoryInterface $programRepository,
        protected EventQueryService $eventQueryService,
        protected PublicEventCtaResolver $publicEventCtaResolver,
        protected EventParticipantStateService $participantStateService
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('q', null);
        $statusFilter = $request->input('status');

        $statusMap = [
            'live_now'             => 'live',
            'open_registration'    => 'registration_open',
            'registration_closed'  => 'registration_closed',
            'completed'            => 'completed',
            'announced'            => 'published',
        ];

        $dbStatus = $statusFilter ? ($statusMap[$statusFilter] ?? null) : null;

        $events = $this->eventQueryService->getPublishedEvents($searchQuery, $dbStatus);

        $events->setCollection(
            $events->getCollection()->map(function (Event $event) {
                $slotsRemaining = $this->participantStateService->slotsRemaining($event);
                $segment = $this->resolvePublicSegment($event, $slotsRemaining);

                $event->slots_remaining = $this->participantStateService->normalizedSlotsRemaining($event);
                $event->public_segment = $segment['key'];
                $event->public_status_label = $segment['label'];
                $event->availability_note = $segment['note'];

                return $event;
            })
        );

        return \Inertia\Inertia::render("Events/Index", [
            'searchQuery' => $searchQuery,
            'events' => $events,
        ]);
    }

    private function resolvePublicSegment(Event $event, string|int $slotsRemaining): array
    {
        return match ($event->lifecycleStatus()) {
            EventStatus::LIVE => [
                'key' => 'live_now',
                'label' => 'Live Now',
                'note' => 'This event is currently in session.',
            ],
            EventStatus::REGISTRATION_OPEN => $slotsRemaining === 'Full'
                ? [
                    'key' => 'waitlist_open',
                    'label' => 'Waitlist Open',
                    'note' => 'Registration capacity is full. New attendees will join the waitlist.',
                ]
                : [
                    'key' => 'open_registration',
                    'label' => 'Open Registration',
                    'note' => 'Registration is currently available.',
                ],
            EventStatus::PUBLISHED => [
                'key' => 'announced',
                'label' => 'Announced',
                'note' => 'This event is public, but registration has not opened yet.',
            ],
            EventStatus::REGISTRATION_CLOSED => [
                'key' => 'registration_closed',
                'label' => 'Registration Closed',
                'note' => 'Public details are still available, but new attendee registration is closed.',
            ],
            EventStatus::COMPLETED => [
                'key' => 'completed',
                'label' => 'Completed',
                'note' => 'This event has ended and remains visible for reference.',
            ],
            default => [
                'key' => 'announced',
                'label' => 'Announced',
                'note' => 'This event is public.',
            ],
        };
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try {
            $event = $this->programRepository->findProgramsBySlug($slug);

            // Load speakers relationship
            $event->load('speakers.user');

            // Calculate slots remaining
            $slotsRemaining = $this->participantStateService->slotsRemaining($event);

            // Check if user is registered
            $isRegistered = false;
            $registrationStatus = null;
            $revokeCount = 0;
            if (auth()->check()) {
                $userId = auth()->id();
                $isRegistered = $this->participantStateService->isRegistered($event, $userId);
                $registrationStatus = $this->participantStateService->registrationStatusForUser($event, $userId);
                $revokeCount = $this->participantStateService->getRevokeCount($event, $userId);
            }

            // Append calculated values to event
            // Transform 'Unlimited' to null for frontend to interpret as truly unlimited
            // Transform 'Full' to 0 for frontend to interpret as no slots
            $event->slots_remaining = $this->participantStateService->normalizedSlotsRemaining($event);
            $event->is_registered = $isRegistered;
            $event->registration_status = $registrationStatus;
            $event->revoke_count = $revokeCount;
            $event->attendee_workspace_url = auth()->check()
                ? route('user.events.show', $event->slug)
                : null;
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
                'meeting_link' => data_get($event->metadata, 'meeting_link'),
                'access_notes' => data_get($event->metadata, 'access_notes'),
            ];

            return \Inertia\Inertia::render("Events/Show", [
                'event' => $event,
                'primary_cta' => $this->publicEventCtaResolver->resolve($event, auth()->user()),
            ]);
        } catch (\Exception $e) {
            abort(404,"Event does not exist");
        }
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
