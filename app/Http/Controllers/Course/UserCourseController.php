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
    public function index(): View
    {
        $courses = $this->courseRepository->getPublishedCourses();

        return view('courses.index', compact('courses'));
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
    public function show(Course $course): View
    {
        $courseWithRelations = $this->courseRepository->getWithRelations($course->id, [
            'instructor',
            'modules.lessons',
            'outcomes',
            'requirements',
            'students',
        ]);

        return view('courses.course-detail', ['course' => $courseWithRelations]);
    }

    /**
     * Course learning interface for enrolled students
     */
    public function learn(Course $course, $lesson = null): View|RedirectResponse
    {
        $user = auth()->user();

        // Check enrollment
        if (! $user || ! $this->courseRepository->isUserEnrolled($user, $course)) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'You must be enrolled in this course to access the learning interface.');
        }

        // Load course with relationships
        $courseWithLessons = $this->courseRepository->getWithRelations($course->id, [
            'modules.lessons' => function ($query) {
                $query->orderBy('order_index');
            },
        ]);

        // Get course progress
        $progressData = $this->progressService->getCourseProgress($user, $courseWithLessons);

        // Handle lesson navigation logic
        $lessonNavigation = $this->buildLessonNavigation($courseWithLessons, $lesson);

        return view('courses.learn', [
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
