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
        return \Inertia\Inertia::render("Events/Index", [
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

            // Load speakers relationship
            $event->load('speakers.user');

            // Calculate slots remaining
            $slotsRemaining = $event->slotsRemaining();

            // Check if user is registered
            $isRegistered = false;
            $revokeCount = 0;
            if (auth()->check()) {
                $isRegistered = $event->isRegistered();
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
            $event->revoke_count = $revokeCount;

            // Generate signed route for speaker application if applicable
            $signedSpeakerRoute = null;
            if ($event->is_allowing_application) {
                $signedSpeakerRoute = \URL::signedRoute('event.speakers.apply', [$event]);
            }

            return \Inertia\Inertia::render("Events/Show", [
                'event' => $event,
                'signed_speaker_route' => $signedSpeakerRoute,
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
