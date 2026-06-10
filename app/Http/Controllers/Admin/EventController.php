<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\SpeakerInviteRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Models\Event;
use App\Models\User;
use App\Services\Event\EventCrudService;
use App\Services\Event\EventQueryService;
use App\Services\Event\EventRegistrationService;
use App\Services\Event\EventSpeakerInvitationService;
use App\Services\Event\SpeakerService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected EventQueryService $eventQueryService,
        protected EventCrudService $eventCrudService,
        protected EventSpeakerInvitationService $eventSpeakerInvitationService,
        protected EventRegistrationService $eventRegistrationService,
        protected SpeakerService $speakerService
    ) {

    }

    public function index()
    {
        $this->authorize('viewAny', Event::class);

        $query = request()->query('status');
        $includePaymentMetrics = Auth::user()?->can(EventPermissionsEnum::VIEW_PAYMENTS->value) ?? false;

        return \Inertia\Inertia::render('Admin/Events/Index', [
            'events' => $this->eventQueryService->getEventsCreatedByUser($query, $includePaymentMetrics),
            'capabilities' => [
                'canCreate' => Auth::user()?->can(EventPermissionsEnum::CREATE->value) ?? false,
                'canUpdateAny' => Auth::user()?->hasAnyPermission([
                    EventPermissionsEnum::UPDATE_ANY->value,
                    EventPermissionsEnum::PUBLISH->value,
                    EventPermissionsEnum::CANCEL->value,
                ]) ?? false,
                'canViewPayments' => $includePaymentMetrics,
            ],
        ]);
    }

    public function create()
    {
        $this->authorize('create', Event::class);

        return \Inertia\Inertia::render('Admin/Events/Create');
    }

    public function show(Event $event)
    {
        $this->authorize('view', $event);

        $capabilities = [
            'canUpdate' => auth()->user()?->can('update', $event) ?? false,
            'canDelete' => auth()->user()?->can('delete', $event) ?? false,
            'canManageSpeakers' => auth()->user()?->can('manageSpeakers', $event) ?? false,
            'canManageAttendees' => auth()->user()?->can('manageAttendees', $event) ?? false,
            'canManageWaitlist' => auth()->user()?->can('manageWaitlist', $event) ?? false,
            'canManageResources' => auth()->user()?->can('manageResources', $event) ?? false,
            'canViewPayments' => auth()->user()?->can('viewPayments', $event) ?? false,
        ];

        $event->load([
            'speakers.user',
            'resources',
            'attendees',
            'speakerApplications.user',
            'speakerApplications.speaker.user',
        ]);

        if ($capabilities['canViewPayments']) {
            $event->load([
                'transactions' => fn ($query) => $query->latest('paid_at')->latest('created_at'),
                'transactions.user',
            ]);
        } else {
            $event->setRelation('transactions', collect());
        }

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

        $speakers = $this->speakerService->fetchSpeakers();

        return \Inertia\Inertia::render('Admin/Events/View', compact('event', 'speakers', 'capabilities'));
    }
    public function store(CreateEventRequest $request)
    {
        $this->authorize('create', Event::class);

        $program_cover = $request->file('program_cover');
        $validated = $request->validated();
        $event = $this->eventCrudService->createEvent($validated, $program_cover);
        if ($event) {
            return to_route('admin.events.index')->with([
                'type' => 'success',
                'message' => 'Event created successfully.'
            ]);
        }
        return redirect()->back()->withInput()->with([
            'type' => 'error',
            'message' => 'Failed to create event. Please try again.'
        ]);
    }

    public function edit(string $slug)
    {
        $event = Event::findBySlug($slug)->firstOrFail();
        $this->authorize('update', $event);

        return \Inertia\Inertia::render('Admin/Events/Edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $this->authorize('update', $event);

        $program_cover = $request->file('program_cover');
        $validated = $request->validated();
        $event = $this->eventCrudService->updateEvent($validated, $event, $program_cover);

        if ($event) {
            return to_route('admin.events.index')->with([
                'type' => 'success',
                'message' => 'Event updated successfully.'
            ]);
        }
        return redirect()->back()->withInput()->with([
            'type' => 'error',
            'message' => 'Failed to update event. Please try again.'
        ]);
    }

    public function destroy(Event $event)
    {
        $this->authorize('delete', $event);

        if ($this->eventCrudService->deleteEvent($event)) {
            return redirect()->route('admin.events.index')->with([
                'type' => 'success',
                'message' => 'Event deleted successfully.'
            ]);
        }
        return redirect()->back()->with([
            'type' => 'error',
            'message' => 'Failed to delete event. Please try again.'
        ]);
    }

    public function massDelete(Request $request)
    {
        abort_unless(
            Auth::user()?->hasPermissionTo(\App\Enums\Permissions\EventPermissionsEnum::DELETE_ANY->value),
            403
        );

        $validated = $request->validate([
            'selected_events' => 'required|array',
            'selected_events.*' => 'exists:events,id'
        ]);
        if ($this->eventCrudService->deleteMany($validated['selected_events'])) {
            return redirect()->route('admin.events.index')->with([
                'type' => 'success',
                'message' => 'Selected events deleted successfully.'
            ]);
        }
        return redirect()->back()->with([
            'type' => 'error',
            'message' => 'Failed to delete selected events. Please try again.'
        ]);
    }

    public function inviteSpeaker(SpeakerInviteRequest $request, Event $event)
    {
        $this->authorize('manageSpeakers', $event);

        $invitation = $this->eventSpeakerInvitationService->inviteSpeakerToEvent($event, $request->validated());
        if ($invitation === true) {
            return redirect()->back()->with([
                'type' => 'success',
                'message' => 'Speaker invited successfully.'
            ]);
        }
        if ($invitation === 'already_invited') {
            return redirect()->back()->with([
                'type'=> 'error',
                'message'=> 'Speaker has already been invited to this event.'
                ]);
        }
        return redirect()->back()->with([
            'type'=> 'error',
            'message'=> 'Failed to invite speaker. Please try again.'
        ]);
    }

    public function promoteWaitlistedAttendee(Event $event, User $user)
    {
        $this->authorize('manageWaitlist', $event);

        $result = $this->eventRegistrationService->promoteWaitlistedAttendee($event, $user->id);

        if ($result === true) {
            return redirect()->back()->with([
                'type' => 'success',
                'message' => 'Waitlisted attendee promoted successfully.',
            ]);
        }

        if ($result === 'no_capacity') {
            return redirect()->back()->with([
                'type' => 'error',
                'message' => 'No seat is currently available for promotion.',
            ]);
        }

        if ($result === 'invalid_status') {
            return redirect()->back()->with([
                'type' => 'error',
                'message' => 'Only waitlisted attendees can be promoted.',
            ]);
        }

        return redirect()->back()->with([
            'type' => 'error',
            'message' => 'Failed to promote attendee. Please try again.',
        ]);
    }


}
