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
        return view('admin.events.index', [
            'events' => $this->eventService->getEventsCreatedByUser()
        ]);
    }

    public function create()
    {
        return view('admin.events.create-event');
    }

    public function show(Event $event)
    {
        $event->load('speakers', "resources");
        $speakers = $this->speakerService->fetchSpeakers();

        return view("admin.events.view-event", compact('event', 'speakers'));
    }
    public function store(CreateEventRequest $request)
    {
        $event = $this->eventService->createEvent($request);
        if ($event) {
            return redirect()->back()->with([
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
        return view('admin.events.edit-event', compact('event'));
    }

    public function update(UpdateEventRequest $request, Event $event)
    {
        $event = $this->eventService->updateEvent($request, $event);

        if ($event) {
            return redirect()->back()->with([
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


    public function addEventToCalender(Event $event)
    {

    }
}

