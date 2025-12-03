<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Services\Course\CourseCategoryService;
use App\Services\Course\CourseService;
use App\Services\Instructors\InstructorStatsService;
use App\Services\VimeoService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstructorCourseController extends Controller
{
    use AuthorizesRequests;
    
    public function __construct(
        public CourseService $courseService,
        public InstructorStatsService $instructorStatsService,
        public CourseCategoryService $courseCategoryService,
    ) {
    }
    public function index()
    {
        $instructorId = auth()->user()->id;
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
        
        return view('instructors.courses.show', compact('course'));
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
    
    public function updateModule(Request $request, Course $course, $moduleId)
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
    
    public function deleteModule(Course $course, $moduleId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);

        try {
            // Delete all Vimeo videos in this module's lessons
            $lessons = $module->lessons()->where('type', 'video')->whereNotNull('vimeo_id')->get();

            if ($lessons->isNotEmpty()) {
                $vimeoService = app(VimeoService::class);
                $deletedCount = 0;

                foreach ($lessons as $lesson) {
                    \Log::info("Attempting to delete Vimeo video from module deletion", [
                        'module_id' => $module->id,
                        'lesson_id' => $lesson->id,
                        'vimeo_id' => $lesson->vimeo_id
                    ]);

                    if ($vimeoService->deleteVideo($lesson->vimeo_id)) {
                        $deletedCount++;
                    }
                }

                \Log::info("Deleted Vimeo videos from module", [
                    'module_id' => $module->id,
                    'videos_deleted' => $deletedCount,
                    'total_videos' => $lessons->count()
                ]);
            }

            $module->delete();

            return to_route('instructor.courses.builder', $course->slug)
                ->with(['type' => 'success', 'message' => 'Module deleted successfully']);

        } catch (\Exception $e) {
            \Log::error("Error deleting module", [
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
    public function createLesson(Course $course, $moduleId)
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

    public function storeLesson(Request $request, Course $course, $moduleId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,pdf,link',
            'description' => 'nullable|string|max:1000',
            'video_field' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/x-matroska|max:512000',
            'content_path' => 'nullable|file|mimes:pdf|max:10240',
            'link_url' => 'nullable|url|max:500',
            'is_preview' => 'nullable|boolean',
            'has_instruction' => 'nullable|boolean',
            'assignment_instructions' => 'nullable|string|max:2000',
        ]);

        try {
            $order = $module->lessons()->max('order') + 1;
            $contentPath = null;
            $vimeoId = null;
            $videoStatus = null;
            $videoUploadedAt = null;

            // Handle video upload to Vimeo (ASYNC)
            if ($validated['type'] === 'video' && $request->hasFile('video_field')) {
                $videoFile = $request->file('video_field');

                // Store video temporarily in storage/app/temp-videos
                $tempPath = $videoFile->store('temp-videos', 'local');

                // Create lesson with pending status first
                $lesson = $module->lessons()->create([
                    'title' => $validated['title'],
                    'type' => $validated['type'],
                    'description' => $validated['description'] ?? '',
                    'content_path' => null,
                    'vimeo_id' => null,
                    'video_status' => 'pending', // Will be updated by job
                    'video_uploaded_at' => null,
                    'is_preview' => $validated['is_preview'] ?? false,
                    'assignment_instructions' => $validated['assignment_instructions'] ?? null,
                    'order' => $order
                ]);

                // Dispatch background job for Vimeo upload
                \App\Jobs\ProcessVideoUpload::dispatch(
                    $lesson->id,
                    $tempPath,
                    $validated['title'],
                    $validated['description'] ?? null
                );

                \Log::info('Video upload job dispatched', [
                    'lesson_id' => $lesson->id,
                    'temp_path' => $tempPath,
                ]);

                return to_route('instructor.courses.builder', $course->slug)
                    ->with('message', 'Lesson created successfully! Video is being uploaded in the background and will be available shortly.');
            }

            // Handle PDF upload
            if ($validated['type'] === 'pdf' && $request->hasFile('content_path')) {
                $pdfFile = $request->file('content_path');
                $contentPath = $pdfFile->store('lessons/pdfs', 'public');
            }

            // Handle link type
            if ($validated['type'] === 'link' && !empty($validated['link_url'])) {
                $contentPath = $validated['link_url'];
            }

            $module->lessons()->create([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? '',
                'content_path' => $contentPath,
                'vimeo_id' => $vimeoId,
                'video_status' => $videoStatus,
                'video_uploaded_at' => $videoUploadedAt,
                'is_preview' => $validated['is_preview'] ?? false,
                'assignment_instructions' => $validated['assignment_instructions'] ?? null,
                'order' => $order
            ]);

            return to_route('instructor.courses.builder', $course->slug)
                ->with('message', 'Lesson created successfully');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Error creating lesson: ' . $e->getMessage());
        }
    }

    public function editLesson(Course $course, $moduleId, $lessonId)
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

    public function updateLesson(Request $request, Course $course, $moduleId, $lessonId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);
        $lesson = $module->lessons()->findOrFail($lessonId);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,pdf,link',
            'description' => 'nullable|string|max:1000',
            'content_path' => 'nullable|string|max:500',
            'duration' => 'nullable|integer|min:0'
        ]);

        try {
            $lesson->update([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? '',
                'content_path' => $validated['content_path'] ?? null,
            ]);

            return to_route('instructor.courses.builder', $course->slug)
                ->with(['type' => 'success', 'message' => 'Lesson updated successfully']);

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with(['type' => 'error', 'message' => 'Error updating lesson: ' . $e->getMessage()]);
        }
    }

    public function deleteLesson(Course $course, $moduleId, $lessonId)
    {
        $this->authorize('update', $course);

        $module = $course->modules()->findOrFail($moduleId);
        $lesson = $module->lessons()->findOrFail($lessonId);

        try {
            // Delete Vimeo video if it exists
            if ($lesson->type === 'video' && $lesson->vimeo_id) {
                \Log::info("Attempting to delete Vimeo video", [
                    'lesson_id' => $lesson->id,
                    'vimeo_id' => $lesson->vimeo_id
                ]);

                $vimeoService = app(VimeoService::class);
                $deleted = $vimeoService->deleteVideo($lesson->vimeo_id);

                if (!$deleted) {
                    \Log::warning("Vimeo video deletion failed, but continuing with lesson deletion", [
                        'lesson_id' => $lesson->id,
                        'vimeo_id' => $lesson->vimeo_id
                    ]);
                }
            }

            $lesson->delete();

            return to_route('instructor.courses.builder', $course->slug)
                ->with(['type' => 'success', 'message' => 'Lesson deleted successfully']);

        } catch (\Exception $e) {
            \Log::error("Error deleting lesson", [
                'lesson_id' => $lessonId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()
                ->with(['type' => 'error', 'message' => 'Error deleting lesson: ' . $e->getMessage()]);
        }
    }
    
    public function reorderLessons(Request $request, Course $course, $moduleId)
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
