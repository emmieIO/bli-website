<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Http\Requests\CreateLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use App\Models\Course;
use App\Services\Course\CourseCategoryService;
use App\Services\Course\CourseService;
use App\Services\Course\LessonService;
use App\Services\Instructors\InstructorStatsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class InstructorCourseController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        public CourseService $courseService,
        public InstructorStatsService $instructorStatsService,
        public CourseCategoryService $courseCategoryService,
        public LessonService $lessonService,
    ) {
    }
    public function index()
    {
        $instructorId = Auth::id();
        // courses count
        // total students enrolled
        // total earnings
        //  Average rating
        $courses = $this->courseService->fetchInstructorCourses($instructorId);
        $instructorStats = $this->instructorStatsService->getInstructorStats($instructorId);
        return Inertia::render("Instructor/Courses/Index", compact("courses", "instructorStats"));
    }

    public function create()
    {
        $categories = $this->courseCategoryService->fetchAll();
        $levels = \App\Enums\CourseLevel::options();
        return \Inertia\Inertia::render('Instructor/Courses/Create', compact('categories', 'levels'));
    }

    public function store(CreateCourseRequest $request)
    {
        $this->authorize('create', Course::class);

        try {
            $course = $this->courseService->createCourse(
                $request->validated(),
                $request->file('thumbnail'),
                $request->file('preview_video')
            );

            if ($course) {
                return redirect()->route('instructor.courses.show', $course)->with([
                    "message" => "Course created successfully! You can now add modules and lessons.",
                    "type" => "success"
                ]);
            }
        } catch (\Exception $e) {
            return back()->withInput()->with([
                "message" => "Error creating course: " . $e->getMessage(),
                "type" => "error"
            ]);
        }

        return back()->withInput()->with([
            "message" => "Failed to create course. Please try again.",
            "type" => "error"
        ]);
    }

    public function show(Course $course)
    {
        $this->authorize('view', $course);

        // Load course with relationships for detailed view
        $course->load(['modules.lessons', 'requirements', 'outcomes', 'category', 'students']);

        return Inertia::render('Instructor/Courses/Show', compact('course'));
    }

    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $categories = $this->courseCategoryService->fetchAll();
        $levels = \App\Enums\CourseLevel::options();
        return \Inertia\Inertia::render('Instructor/Courses/Edit', compact('course', 'categories', 'levels'));
    }

    public function update(CreateCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);

        try {
            $course = $this->courseService->updateCourse(
                $course,
                $request->validated(),
                $request->file('thumbnail_path'),
                $request->file('preview_video')
            );

            return redirect()->route('instructor.courses.index')->with([
                "message" => "Course updated successfully!",
                "type" => "success"
            ]);
        } catch (\Exception $e) {
            return back()->withInput()->with([
                "message" => "Error updating course: " . $e->getMessage(),
                "type" => "error"
            ]);
        }
    }

    public function builder(Course $course)
    {
        $this->authorize('update', $course);

        // Load course with modules, lessons, requirements, and outcomes
        $course->load([
            'modules.lessons' => function($query) {
                $query->orderBy('order');
            },
            'requirements',
            'outcomes',
            'category',
            'instructor'
        ]);

        return \Inertia\Inertia::render('Instructor/Courses/Builder', compact('course'));
    }

    public function submitForReview(Course $course)
    {
        $this->authorize('update', $course);

        try {
            // Only allow submission if course is in draft status
            if ($course->status !== \App\Enums\ApplicationStatus::DRAFT) {
                return back()->with([
                    "message" => "Only draft courses can be submitted for review.",
                    "type" => "error"
                ]);
            }

            // Update course status to pending review
            $course->update(['status' => \App\Enums\ApplicationStatus::PENDING]);

            // Notify all admins about the course submission
            $admins = \App\Models\User::role('admin')->get();
            foreach ($admins as $admin) {
                $admin->notify(new \App\Notifications\CourseSubmittedForReview($course));
            }

            return back()->with([
                "message" => "Course submitted for review successfully! You'll be notified once it's approved.",
                "type" => "success"
            ]);
        } catch (\Exception $e) {
            return back()->with([
                "message" => "Error submitting course for review: " . $e->getMessage(),
                "type" => "error"
            ]);
        }
    }

    // Module Management Methods
    public function storeModule(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            $order = $course->modules()->max('order') + 1;

            $course->modules()->create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? '',
                'order' => $order
            ]);

            return to_route('instructor.courses.builder', $course->slug)
                ->with('message', 'Module created successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error creating module: ' . $e->getMessage());
        }
    }

    public function updateModule(Request $request, Course $course, int $moduleId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            $module->update([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? ''
            ]);

            return to_route('instructor.courses.builder', $course->slug)
                ->with('message', 'Module updated successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error updating module: ' . $e->getMessage());
        }
    }

    public function deleteModule(Course $course, int $moduleId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->with('lessons')->findOrFail($moduleId);

        try {
            foreach ($module->lessons as $lesson) {
                $this->lessonService->deleteLesson($lesson);
            }

            $module->delete();

            return to_route('instructor.courses.builder', $course->slug)
                ->with(['type' => 'success', 'message' => 'Module deleted successfully']);

        } catch (\Exception $e) {
            Log::error("Error deleting module", [
                'module_id' => $moduleId,
                'error' => $e->getMessage()
            ]);

            return back()
                ->with(['type' => 'error', 'message' => 'Error deleting module: ' . $e->getMessage()]);
        }
    }

    public function reorderModules(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'modules' => 'required|array',
            'modules.*' => 'required|integer|exists:course_modules,id'
        ]);

        try {
            foreach ($validated['modules'] as $index => $moduleId) {
                $course->modules()->where('id', $moduleId)->update(['order' => $index + 1]);
            }

            return to_route('instructor.courses.builder', $course->slug)
                ->with('message', 'Modules reordered successfully');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error reordering modules: ' . $e->getMessage());
        }
    }

    // Lesson Management Methods
    public function createLesson(Course $course, int $moduleId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);

        $lessontypes = [
            ['label' => 'Video', 'value' => 'video'],
            ['label' => 'PDF', 'value' => 'pdf'],
            ['label' => 'Link', 'value' => 'link'],
        ];

        return Inertia::render('Instructor/Courses/AddLesson', [
            'module' => $module->load('course'),
            'lessontypes' => $lessontypes,
        ]);
    }

    public function storeLesson(CreateLessonRequest $request, Course $course, int $moduleId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);

        try {
            $this->lessonService->createLesson(
                $module,
                $request->validated(),
                $request->file('content_path'),
                $request->file('video_field')
            );

            return to_route('instructor.courses.builder', $course->slug)
                ->with(['type' => 'success', 'message' => 'Lesson created successfully.']);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with(['type' => 'error', 'message' => 'Error creating lesson: ' . $e->getMessage()]);
        }
    }

    public function editLesson(Course $course, int $moduleId, int $lessonId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);
        $lesson = $module->lessons()->findOrFail($lessonId);

        $lessontypes = [
            ['label' => 'Video', 'value' => 'video'],
            ['label' => 'PDF', 'value' => 'pdf'],
            ['label' => 'Link', 'value' => 'link'],
        ];

        return Inertia::render('Instructor/Courses/EditLesson', [
            'module' => $module->load('course'),
            'lesson' => $lesson,
            'lessontypes' => $lessontypes,
        ]);
    }

    public function updateLesson(UpdateLessonRequest $request, Course $course, int $moduleId, int $lessonId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);
        $lesson = $module->lessons()->findOrFail($lessonId);

        try {
            $this->lessonService->updateLesson(
                $lesson,
                $request->validated(),
                $request->file('content_path'),
                $request->file('video_field')
            );

            return to_route('instructor.courses.builder', $course->slug)
                ->with(['type' => 'success', 'message' => 'Lesson updated successfully']);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with(['type' => 'error', 'message' => 'Error updating lesson: ' . $e->getMessage()]);
        }
    }

    public function deleteLesson(Course $course, int $moduleId, int $lessonId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);
        $lesson = $module->lessons()->findOrFail($lessonId);

        try {
            $this->lessonService->deleteLesson($lesson);

            return to_route('instructor.courses.builder', $course->slug)
                ->with(['type' => 'success', 'message' => 'Lesson deleted successfully']);

        } catch (\Exception $e) {
            Log::error("Error deleting lesson", [
                'lesson_id' => $lessonId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with(['type' => 'error', 'message' => 'Error deleting lesson: ' . $e->getMessage()]);
        }
    }

    public function reorderLessons(Request $request, Course $course, int $moduleId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);

        $validated = $request->validate([
            'lessons' => 'required|array',
            'lessons.*' => 'required|integer|exists:lessons,id'
        ]);

        try {
            foreach ($validated['lessons'] as $index => $lessonId) {
                $module->lessons()->where('id', $lessonId)->update(['order' => $index + 1]);
            }

            return to_route('instructor.courses.builder', $course->slug)
                ->with('message', 'Lessons reordered successfully');

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error reordering lessons: ' . $e->getMessage());
        }
    }

    /**
     * Delete a course (instructors can only delete their own draft courses)
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        try {
            $this->courseService->deleteCourse($course);

            return redirect()->route('instructor.courses.index')->with([
                "message" => "Course deleted successfully",
                "type" => "success"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "message" => "Error deleting course: " . $e->getMessage(),
                "type" => "error"
            ]);
        }
    }
}
