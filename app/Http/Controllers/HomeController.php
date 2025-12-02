<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\User;
use App\Models\Transaction;
use App\Models\Event;
use App\Services\Event\EventService;
use App\Services\MiscService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function __construct(
        public EventService $eventService,
        public MiscService $miscService
    ) {}

    public function index()
    {
        $events = $this->eventService->fetchFeaturedEvents();
        $categories = $this->miscService->fetchFiveCategories();

        // Get real platform statistics
        $stats = [
            'total_courses' => Course::where('status', 'published')->count(),
            'total_students' => User::role('student')->count(),
            'total_instructors' => User::role('instructor')->count(),
            'total_enrollments' => Transaction::where('status', 'successful')
                ->whereNotNull('course_id')
                ->count(),
            'active_events' => Event::where('end_date', '>=', now())->count(),
        ];

        return Inertia::render("Index", compact("events", "categories", "stats"));
    }
}
