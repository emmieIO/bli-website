<?php

namespace App\Http\Controllers;

use App\Contracts\ProgramRepositoryInterface;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Services\Event\EventService;
use App\Services\Event\PublicEventCtaResolver;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function __construct(
        protected ProgramRepositoryInterface $programRepository,
        protected EventService $eventService,
        protected PublicEventCtaResolver $publicEventCtaResolver
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('q', null);
        $events = $this->eventService->getPublishedEvents($searchQuery);

        $events->setCollection(
            $events->getCollection()->map(function (Event $event) {
                $slotsRemaining = $event->slotsRemaining();
                $segment = $this->resolvePublicSegment($event, $slotsRemaining);

                $event->slots_remaining = $slotsRemaining === 'Unlimited'
                    ? null
                    : ($slotsRemaining === 'Full' ? 0 : $slotsRemaining);
                $event->public_segment = $segment['key'];
                $event->public_status_label = $segment['label'];
                $event->availability_note = $segment['note'];

                return $event;
            })
        );

        $sections = collect($this->publicSectionDefinitions())
            ->map(function (array $section) use ($events) {
                $matches = $events->getCollection()
                    ->filter(fn (Event $event) => $event->public_segment === $section['key'])
                    ->values();

                return [
                    ...$section,
                    'events' => $matches,
                ];
            })
            ->filter(fn (array $section) => count($section['events']) > 0)
            ->values();

        $segmentCounts = [
            'live_now' => $events->getCollection()->where('public_segment', 'live_now')->count(),
            'open_registration' => $events->getCollection()->where('public_segment', 'open_registration')->count(),
            'waitlist_open' => $events->getCollection()->where('public_segment', 'waitlist_open')->count(),
            'announced' => $events->getCollection()->where('public_segment', 'announced')->count(),
            'registration_closed' => $events->getCollection()->where('public_segment', 'registration_closed')->count(),
            'completed' => $events->getCollection()->where('public_segment', 'completed')->count(),
        ];

        return \Inertia\Inertia::render("Events/Index", [
            'searchQuery' => $searchQuery,
            'segmentCounts' => $segmentCounts,
            'sections' => $sections,
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

    private function publicSectionDefinitions(): array
    {
        return [
            [
                'key' => 'live_now',
                'title' => 'Live Now',
                'description' => 'Events that are currently happening and should take top visibility.',
            ],
            [
                'key' => 'open_registration',
                'title' => 'Open Registration',
                'description' => 'Events actively accepting new attendee registrations.',
            ],
            [
                'key' => 'waitlist_open',
                'title' => 'Waitlist Open',
                'description' => 'Events at capacity where new attendees can still join the queue.',
            ],
            [
                'key' => 'announced',
                'title' => 'Announced',
                'description' => 'Publicly visible events that are not yet open for attendee registration.',
            ],
            [
                'key' => 'registration_closed',
                'title' => 'Registration Closed',
                'description' => 'Events that remain public, but no longer accept new attendee registration.',
            ],
            [
                'key' => 'completed',
                'title' => 'Completed',
                'description' => 'Past public events retained for visibility and reference.',
            ],
        ];
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
            $slotsRemaining = $event->slotsRemaining();

            // Check if user is registered
            $isRegistered = false;
            $registrationStatus = null;
            $revokeCount = 0;
            if (auth()->check()) {
                $isRegistered = $event->isRegistered();
                $registrationStatus = $event->registrationStatusForUser();
                $revokeCount = $event->getRevokeCount();
            }

            // Append calculated values to event
            // Transform 'Unlimited' to null for frontend to interpret as truly unlimited
            // Transform 'Full' to 0 for frontend to interpret as no slots
            if ($slotsRemaining === 'Unlimited') {
                $event->slots_remaining = null;
            } elseif ($slotsRemaining === 'Full') {
                $event->slots_remaining = 0;
            } else {
                $event->slots_remaining = $slotsRemaining; // It's already a number
            }
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
