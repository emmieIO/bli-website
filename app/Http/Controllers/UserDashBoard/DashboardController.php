<?php

namespace App\Http\Controllers\UserDashBoard;

use App\Http\Controllers\Controller;
use App\Models\Course;
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

        // Get enrolled courses
        $enrolledCourses = $user->courseEnrollments()
            ->with(['modules.lessons', 'instructor', 'category'])
            ->get();

        $totalCourses = $enrolledCourses->count();

        // Calculate course statistics
        $inProgress = 0;
        $completed = 0;
        $totalLessons = 0;
        $completedLessons = 0;
        $totalWatchDuration = 0;
        $coursesWithProgress = [];

        foreach ($enrolledCourses as $course) {
            $courseLessons = $course->modules->flatMap(function ($module) {
                return $module->lessons;
            });
            $courseLessonCount = $courseLessons->count();
            $totalLessons += $courseLessonCount;

            // Skip courses without lessons, but still add them to the array for display
            if ($courseLessonCount === 0) {
                $coursesWithProgress[] = [
                    'id' => $course->id,
                    'title' => $course->title,
                    'slug' => $course->slug,
                    'thumbnail_path' => $course->thumbnail_path,
                    'instructor' => [
                        'name' => $course->instructor->name ?? 'Unknown Instructor',
                    ],
                    'category' => [
                        'name' => $course->category->name ?? 'Uncategorized',
                    ],
                    'total_lessons' => 0,
                    'completed_lessons' => 0,
                    'completion_percentage' => 0,
                    'status' => 'not_started',
                    'next_lesson' => null,
                ];
                continue;
            }

            // Get completed lessons for this course
            $courseCompletedLessons = $user->lessonProgress()
                ->where('course_id', $course->id)
                ->where('is_completed', true)
                ->count();

            $completedLessons += $courseCompletedLessons;

            // Get watch duration for this course
            $courseWatchDuration = $user->lessonProgress()
                ->where('course_id', $course->id)
                ->sum('watch_duration');

            $totalWatchDuration += $courseWatchDuration;

            // Determine course status
            $completionPercentage = $courseLessonCount > 0
                ? round(($courseCompletedLessons / $courseLessonCount) * 100)
                : 0;

            // Get the next lesson to continue from
            $nextLesson = null;
            $lastProgressedLesson = $user->lessonProgress()
                ->where('course_id', $course->id)
                ->with('lesson')
                ->latest('updated_at')
                ->first();

            if ($lastProgressedLesson && !$lastProgressedLesson->is_completed && $lastProgressedLesson->lesson) {
                $nextLesson = $lastProgressedLesson->lesson;
            } else {
                // Find the first incomplete lesson
                foreach ($course->modules as $module) {
                    foreach ($module->lessons as $lesson) {
                        $lessonProgress = $user->lessonProgress()
                            ->where('lesson_id', $lesson->id)
                            ->first();

                        if (!$lessonProgress || !$lessonProgress->is_completed) {
                            $nextLesson = $lesson;
                            break 2;
                        }
                    }
                }
            }

            $status = 'not_started';
            if ($completionPercentage === 100) {
                $completed++;
                $status = 'completed';
            } elseif ($completionPercentage > 0) {
                $inProgress++;
                $status = 'in_progress';
            }

            $coursesWithProgress[] = [
                'id' => $course->id,
                'title' => $course->title,
                'slug' => $course->slug,
                'thumbnail_path' => $course->thumbnail_path,
                'instructor' => [
                    'name' => $course->instructor->name ?? 'Unknown Instructor',
                ],
                'category' => [
                    'name' => $course->category->name ?? 'Uncategorized',
                ],
                'total_lessons' => $courseLessonCount,
                'completed_lessons' => $courseCompletedLessons,
                'completion_percentage' => $completionPercentage,
                'status' => $status,
                'next_lesson' => $nextLesson ? [
                    'id' => $nextLesson->id,
                    'title' => $nextLesson->title,
                    'slug' => $nextLesson->slug,
                ] : null,
            ];
        }

        // Sort courses: in_progress first, then not_started, then completed
        usort($coursesWithProgress, function ($a, $b) {
            $statusOrder = ['in_progress' => 1, 'not_started' => 2, 'completed' => 3];
            return $statusOrder[$a['status']] <=> $statusOrder[$b['status']];
        });

        // Calculate hours spent (watch_duration is in seconds)
        $hoursSpent = round($totalWatchDuration / 3600, 1);

        $stats = [
            'totalCourses' => $totalCourses,
            'inProgress' => $inProgress,
            'completed' => $completed,
            'hoursSpent' => $hoursSpent,
            'totalLessons' => $totalLessons,
            'completedLessons' => $completedLessons,
        ];

        // Check if user is admin or instructor
        $isAdmin = $user->hasRole(['admin', 'super-admin']);
        $isInstructor = $user->hasRole('instructor');

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
            'courses' => $coursesWithProgress,
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

        // Total courses and development status
        $totalCourses = Course::count();
        $coursesInDevelopment = Course::where('status', 'draft')->count();

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
            'totalCourses' => $totalCourses,
            'totalCoursesBadge' => $coursesInDevelopment > 0
                ? "{$coursesInDevelopment} in development"
                : 'All courses published',
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

        // Courses taught by this instructor
        $coursesTaught = Course::where('instructor_id', $instructor->id)->count();
        $coursesThisMonth = Course::where('instructor_id', $instructor->id)
            ->where('created_at', '>=', $monthStart)
            ->count();

        // Active students (enrolled in instructor's courses)
        $activeStudents = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->where('courses.instructor_id', $instructor->id)
            ->distinct()
            ->count('course_user.user_id');

        $activeStudentsThisWeek = DB::table('course_user')
            ->join('courses', 'course_user.course_id', '=', 'courses.id')
            ->where('courses.instructor_id', $instructor->id)
            ->where('course_user.created_at', '>=', $weekStart)
            ->distinct()
            ->count('course_user.user_id');

        // Get instructor's courses with lesson completion data
        $instructorCourses = Course::where('instructor_id', $instructor->id)
            ->with(['modules.lessons'])
            ->get();

        $totalAssignments = 0;
        $completedAssignments = 0;

        foreach ($instructorCourses as $course) {
            foreach ($course->modules as $module) {
                foreach ($module->lessons as $lesson) {
                    if ($lesson->assignment_instructions) {
                        $totalAssignments++;
                        // Count as graded if lesson is completed by any student
                        $completedCount = DB::table('lesson_progress')
                            ->where('lesson_id', $lesson->id)
                            ->where('is_completed', true)
                            ->count();
                        if ($completedCount > 0) {
                            $completedAssignments++;
                        }
                    }
                }
            }
        }

        $assignmentsThisWeek = 20;

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
            'coursesTaught' => $coursesTaught,
            'coursesTaughtDescription' => $coursesThisMonth > 0
                ? "{$coursesThisMonth} new courses this month"
                : 'No new courses this month',
            'activeStudents' => $activeStudents,
            'activeStudentsDescription' => $activeStudentsThisWeek > 0
                ? "+{$activeStudentsThisWeek} active this week"
                : 'No new students this week',
            'assignmentsGraded' => $completedAssignments,
            'assignmentsGradedDescription' => $assignmentsThisWeek > 0
                ? "{$assignmentsThisWeek} graded this week"
                : 'No assignments graded this week',
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
