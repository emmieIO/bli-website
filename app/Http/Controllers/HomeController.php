<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Event;
use App\Services\Event\EventQueryService;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(
        public EventQueryService $eventQueryService
    ) {}

    public function index()
    {
        $events = $this->eventQueryService->fetchFeaturedEvents();

        // Get real platform statistics
        $stats = [
            'total_students' => User::role('student')->count(),
            'total_instructors' => User::role('instructor')->count(),
            'active_events' => Event::where('end_date', '>=', now())->count(),
        ];

        return Inertia::render("Index", compact("events", "stats"));
    }
}
