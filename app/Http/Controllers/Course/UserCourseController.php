<?php

namespace App\Http\Controllers\Course;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\Services\LearningProgressServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserCourseController extends Controller
{
    public function __construct(
        private CourseRepositoryInterface $courseRepository,
        private LearningProgressServiceInterface $progressService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = $this->courseRepository->getPublishedCourses();

        return \Inertia\Inertia::render('Courses/Index', compact('courses'));
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
    public function show(Course $course)
    {
        // Only allow viewing approved courses for public users
        // Instructors and admins can view their own courses via separate routes
        if ($course->status !== \App\Enums\ApplicationStatus::APPROVED) {
            abort(404, 'Course not found or not available.');
        }

        $courseWithRelations = $this->courseRepository->getWithRelations($course->id, [
            'instructor.ratings',
            'modules.lessons',
            'outcomes',
            'requirements',
            'students',
            'category',
        ]);

        // Check if user is enrolled
        $isEnrolled = false;
        if (auth()->check()) {
            $isEnrolled = $this->courseRepository->isUserEnrolled(auth()->user(), $course);
        }

        // Calculate instructor average rating
        $instructorRating = $courseWithRelations->instructor->ratings->avg('rating') ?? 0;
        $instructorRatingCount = $courseWithRelations->instructor->ratings->count();

        // Calculate course rating (based on instructor ratings for courses they teach)
        $courseRating = round($instructorRating, 1);

        return \Inertia\Inertia::render('Courses/CourseDetail', [
            'course' => $courseWithRelations,
            'isEnrolled' => $isEnrolled,
            'courseRating' => $courseRating,
            'ratingCount' => $instructorRatingCount,
            'instructorRating' => round($instructorRating, 1),
        ]);
    }

    /**
     * Course learning interface for enrolled students
     */
    public function learn(Course $course, $lesson = null)
    {
        $user = auth()->user();

        // Check enrollment
        if (! $user || ! $this->courseRepository->isUserEnrolled($user, $course)) {
            return redirect()->route('courses.show', $course->slug)
                ->with('error', 'You must be enrolled in this course to access the learning interface.');
        }

        // Load course with relationships
        $courseWithLessons = $this->courseRepository->getWithRelations($course->id, [
            'modules.lessons' => function ($query) {
                $query->orderBy('order');
            },
            'outcomes',
        ]);

        // If no lesson is specified, redirect to the first lesson
        if (!$lesson) {
            $firstLesson = $courseWithLessons->modules->first()->lessons->first();
            if ($firstLesson) {
                return redirect()->route('courses.learn', ['course' => $course->slug, 'lesson' => $firstLesson->id]);
            }
        }

        // Get course progress
        $progressData = $this->progressService->getCourseProgress($user, $courseWithLessons);

        // Add completion status to each lesson
        foreach ($courseWithLessons->modules as $module) {
            foreach ($module->lessons as $lessonItem) {
                $lessonItem->completed = $this->progressService->isLessonCompleted($user, $lessonItem);
            }
        }

        // Handle lesson navigation logic
        $lessonNavigation = $this->buildLessonNavigation($courseWithLessons, $lesson);

        return \Inertia\Inertia::render('Courses/Learn', [
            'course' => $courseWithLessons,
            'currentLesson' => $lessonNavigation['current'],
            'previousLesson' => $lessonNavigation['previous'],
            'nextLesson' => $lessonNavigation['next'],
            'progress' => $progressData->progressPercentage,
            'totalLessons' => $progressData->totalLessons,
            'completedLessons' => $progressData->completedLessons,
        ]);
    }

    /**
     * Build lesson navigation data
     */
    private function buildLessonNavigation(Course $course, $lessonId = null): array
    {
        $allLessons = collect();

        // Flatten all lessons from all modules
        foreach ($course->modules as $module) {
            foreach ($module->lessons as $lesson) {
                $allLessons->push($lesson);
            }
        }

        $currentLesson = null;
        $previousLesson = null;
        $nextLesson = null;

        if ($lessonId) {
            $currentLesson = $allLessons->firstWhere('id', $lessonId);

            if ($currentLesson) {
                $currentIndex = $allLessons->search(function ($item) use ($currentLesson) {
                    return $item->id === $currentLesson->id;
                });

                $previousLesson = $currentIndex > 0 ? $allLessons[$currentIndex - 1] : null;
                $nextLesson = $currentIndex < $allLessons->count() - 1 ? $allLessons[$currentIndex + 1] : null;
            }
        } else {
            // Default to first lesson if available
            $currentLesson = $allLessons->first();
            $nextLesson = $allLessons->count() > 1 ? $allLessons[1] : null;
        }

        return [
            'current' => $currentLesson,
            'previous' => $previousLesson,
            'next' => $nextLesson,
        ];
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
     * Enroll user in a course
     */
    public function enroll(Course $course)
    {
        $user = auth()->user();

        if (!$user) {
            return redirect()->route('login')->with([
                'message' => 'Please login to enroll in this course',
                'type' => 'info'
            ]);
        }

        // Authorization check - ensures course is approved and user can enroll
        if (!$user->can('enroll', $course)) {
            return redirect()->route('courses.show', $course->slug)->with([
                'message' => 'This course is not available for enrollment',
                'type' => 'error'
            ]);
        }

        // Check if already enrolled
        if ($this->courseRepository->isUserEnrolled($user, $course)) {
            return redirect()->route('courses.show', $course->slug)->with([
                'message' => 'You are already enrolled in this course',
                'type' => 'info'
            ]);
        }

        // For now, only allow free courses to be enrolled directly
        // Paid courses should go through payment gateway
        if ($course->price > 0 && !$course->is_free) {
            return redirect()->route('courses.show', $course->slug)->with([
                'message' => 'Please complete payment to enroll in this course',
                'type' => 'warning'
            ]);
        }

        // Enroll the user
        $this->courseRepository->enrollUser($user, $course);

        return redirect()->route('courses.learn', ['course' => $course->slug])->with([
            'message' => 'Successfully enrolled! Start learning now.',
            'type' => 'success'
        ]);
    }

    /**
     * Mark lesson as completed
     */
    public function markLessonComplete(Course $course, $lesson)
    {
        $user = auth()->user();
        $lessonModel = \App\Models\Lesson::with('courseModule')->findOrFail($lesson);

        // Verify enrollment
        if (!$this->courseRepository->isUserEnrolled($user, $course)) {
            return back()->with('error', 'You must be enrolled to mark lessons as complete');
        }

        $this->progressService->markLessonCompleted($user, $lessonModel);

        return back()->with('success', 'Lesson marked as completed');
    }

    /**
     * Update lesson progress
     */
    public function updateLessonProgress(Request $request, Course $course, $lesson)
    {
        $user = auth()->user();
        $lessonModel = \App\Models\Lesson::findOrFail($lesson);

        // Verify enrollment
        if (!$this->courseRepository->isUserEnrolled($user, $course)) {
            return response()->json(['error' => 'Not enrolled'], 403);
        }

        $request->validate([
            'current_time' => 'required|numeric|min:0',
            'duration' => 'nullable|numeric|min:0',
            'is_completed' => 'boolean'
        ]);

        $progressData = \App\DTOs\LessonProgressData::fromRequest($request->all());

        $this->progressService->updateLessonProgress($user, $lessonModel, $progressData);

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
