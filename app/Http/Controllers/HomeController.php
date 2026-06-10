<?php

namespace App\Http\Controllers;

use App\Enums\UserRoles;
use App\Models\User;
use App\Models\Event;
use App\Services\Event\EventQueryService;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

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
            'total_students' => $this->countUsersByRole(UserRoles::STUDENT),
            'total_instructors' => $this->countUsersByRole(UserRoles::INSTRUCTOR),
            'active_events' => Event::where('end_date', '>=', now())->count(),
        ];

        return Inertia::render("Index", compact("events", "stats"));
    }

    private function countUsersByRole(UserRoles $role): int
    {
        $roleExists = Role::query()
            ->where('name', $role->value)
            ->where('guard_name', 'web')
            ->exists();

        return $roleExists ? User::role($role->value)->count() : 0;
    }
}
