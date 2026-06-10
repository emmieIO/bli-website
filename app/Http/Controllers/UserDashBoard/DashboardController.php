<?php

namespace App\Http\Controllers\UserDashBoard;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Display the user dashboard with proper statistics
     */
    public function index(Request $request): Response
    {
        $user = $request->user();

        $stats = [
            'myEvents' => $user->events()->count(),
            'upcomingEvents' => $user->events()->where('end_date', '>=', now())->count(),
            'speakerInvitations' => DB::table('speaker_invites')->where('email', $user->email)->count(),
        ];

        // Check if user is admin or instructor
        $isAdmin = $user->hasRole(['admin', 'super-admin']);
        $isInstructor = $user->canAccessInstructorArea();

        $adminStats = null;
        $instructorStats = null;

        if ($isAdmin) {
            $adminStats = $this->getAdminStats();
        }

        if ($isInstructor) {
            $instructorStats = $this->getInstructorStats($user);
        }

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'adminStats' => $adminStats,
            'instructorStats' => $instructorStats,
        ]);
    }

    /**
     * Get admin dashboard statistics
     */
    private function getAdminStats(): array
    {
        $now = now();
        $monthStart = $now->copy()->startOfMonth();

        // Total users and monthly growth
        $totalUsers = User::count();
        $usersThisMonth = User::where('created_at', '>=', $monthStart)->count();
        $userGrowthPercentage = $totalUsers > 0
            ? round(($usersThisMonth / $totalUsers) * 100, 1)
            : 0;

        // Active users (users who logged in or had activity in last 30 days)
        $activeUsers = User::where('updated_at', '>=', $now->copy()->subDays(30))->count();
        $engagementRate = $totalUsers > 0
            ? round(($activeUsers / $totalUsers) * 100)
            : 0;

        // Events scheduled
        $eventsScheduled = Event::where('end_date', '>=', $now)->count();
        $eventsToday = Event::whereDate('start_date', '<=', $now->toDateString())
            ->whereDate('end_date', '>=', $now->toDateString())
            ->count();

        // Total attendees (event participants)
        $totalAttendees = DB::table('event_attendees')->count();
        $attendeesThisMonth = DB::table('event_attendees')
            ->where('created_at', '>=', $monthStart)
            ->count();

        return [
            'totalUsers' => $totalUsers,
            'totalUsersBadge' => $userGrowthPercentage > 0
                ? "+{$userGrowthPercentage}% this month"
                : 'No growth this month',
            'totalUsersBadgeColor' => $userGrowthPercentage > 0 ? '#00a651' : '#6b7280',
            'activeUsers' => $activeUsers,
            'activeUsersBadge' => "{$engagementRate}% engagement",
            'eventsScheduled' => $eventsScheduled,
            'eventsScheduledBadge' => $eventsToday > 0
                ? "{$eventsToday} happening today"
                : 'No events today',
            'eventsScheduledBadgeColor' => $eventsToday > 0 ? '#002147' : '#6b7280',
            'totalAttendees' => $totalAttendees,
            'totalAttendeesBadge' => $attendeesThisMonth > 0
                ? "+{$attendeesThisMonth} this month"
                : 'No new attendees',
            'totalAttendeesBadgeColor' => $attendeesThisMonth > 0 ? '#00a651' : '#6b7280',
        ];
    }

    /**
     * Get instructor dashboard statistics
     */
    private function getInstructorStats(User $instructor): array
    {
        $now = now();
        $monthStart = $now->copy()->startOfMonth();
        $weekStart = $now->copy()->startOfWeek();

        // Upcoming sessions (events where instructor is speaking)
        $upcomingSessions = DB::table('event_speaker')
            ->join('events', 'event_speaker.event_id', '=', 'events.id')
            ->where('event_speaker.speaker_id', $instructor->id)
            ->where('events.start_date', '>', $now)
            ->count();

        $nextSession = DB::table('event_speaker')
            ->join('events', 'event_speaker.event_id', '=', 'events.id')
            ->where('event_speaker.speaker_id', $instructor->id)
            ->where('events.start_date', '>', $now)
            ->orderBy('events.start_date', 'asc')
            ->select('events.start_date')
            ->first();

        // Ratings/feedback received
        $feedbackCount = DB::table('instructor_ratings')
            ->where('instructor_id', $instructor->id)
            ->count();

        $feedbackThisMonth = DB::table('instructor_ratings')
            ->where('instructor_id', $instructor->id)
            ->where('created_at', '>=', $monthStart)
            ->count();

        return [
            'upcomingSessions' => $upcomingSessions,
            'upcomingSessionsDescription' => $nextSession
                ? 'Next session: ' . \Carbon\Carbon::parse($nextSession->start_date)->format('l ga')
                : 'No upcoming sessions',
            'feedbackReceived' => $feedbackCount,
            'feedbackReceivedDescription' => $feedbackThisMonth > 0
                ? "{$feedbackThisMonth} new feedbacks this month"
                : 'No new feedback this month',
        ];
    }
}
