<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\SpeakerInviteRequest;
use App\Http\Requests\UpdateEventRequest;
use App\Models\Event;
use App\Services\Event\EventService;
use App\Services\Event\SpeakerService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(
        protected EventService $eventService,
        protected SpeakerService $speakerService
    ) {

    }

    public function index()
    {
        $query = request()->query('status');
        return \Inertia\Inertia::render('Admin/Events/Index', [
            'events' => $this->eventService->getEventsCreatedByUser($query)
        ]);
    }

    public function create()
    {
        return \Inertia\Inertia::render('Admin/Events/Create');
    }

    public function show(Event $event)
    {
        $event->load('speakers.user', "resources", "attendees");
        $speakers = $this->speakerService->fetchSpeakers();

        return \Inertia\Inertia::render('Admin/Events/View', compact('event', 'speakers'));
    }
    public function store(CreateEventRequest $request)
    {
        $program_cover = $request->file('program_cover');
        $validated = $request->validated();
        $event = $this->eventService->createEvent($validated, $program_cover);
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
        return \Inertia\Inertia::render('Admin/Events/Edit', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $program_cover = $request->file('program_cover');
        $validated = $request->validated();
        $event = $this->eventService->updateEvent($validated, $event, $program_cover);

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
        if ($this->eventService->deleteEvent($event)) {
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
        $validated = $request->validate([
            'selected_events' => 'required|array',
            'selected_events.*' => 'exists:events,id'
        ]);
        if ($this->eventService->deleteMany($validated['selected_events'])) {
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
        $invitation = $this->eventService->inviteSpeakerToEvent($event, $request->validated());
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


}

