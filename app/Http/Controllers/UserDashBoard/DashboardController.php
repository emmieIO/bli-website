<?php

namespace App\Http\Controllers\UserDashBoard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
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

        return Inertia::render('Dashboard/Index', [
            'stats' => $stats,
            'courses' => $coursesWithProgress,
        ]);
    }
}
