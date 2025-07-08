<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateEventRequest;
use App\Services\Event\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function __construct(
        protected EventService $eventService
    ){

    }
    public function index(){
        return view("admin.events.index", [
            'events'=> $this->eventService->getEventsCreatedByUser()
        ]);
    }

    public function create(){
        return view('admin.events.create-event');
    }

    public function store(CreateEventRequest $request){
        $event = $this->eventService->createEvent($request);
        if($event){
            return redirect()->route('admin.events.index')->with([
                'type' => 'success',
                'message' => 'Event created successfully.'
            ]);
        }
        return redirect()->back()->withInput()->with([
            'type' => 'error',
            'message' => 'Failed to create event. Please try again.'
        ]);
    }
}
