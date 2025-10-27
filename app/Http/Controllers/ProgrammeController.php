<?php

namespace App\Http\Controllers;

use App\Contracts\ProgramRepositoryInterface;
use App\Models\Event;
use App\Services\Event\EventService;
use Cache;
use Illuminate\Http\Request;

class ProgrammeController extends Controller
{
    public function __construct(
        protected ProgramRepositoryInterface $programRepository,
        protected EventService $eventService
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $searchQuery = $request->input('q', null);
        $events = $this->eventService->getPublishedEvents($searchQuery);
        return view("upcoming_events.index", [
            'upcomingEvents' => Event::upcoming()->count(),
            'ongoingEvents' => Event::ongoing()->count(),
            'expiredEvents'=> Event::ended()->count(),
            'events' => $events,
        ]);
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
            return view('upcoming_events.show-event',compact("event"));
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
