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
        try {
            \Log::info('storeModule called', [
                'course_id' => $course->id,
                'user_id' => auth()->id(),
                'request_data' => $request->all()
            ]);
            
            $this->authorize('update', $course);
            
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string|max:1000'
            ]);
            
            $order = $course->modules()->max('order') + 1;
            
            $module = $course->modules()->create([
                'title' => $validated['title'],
                'description' => $validated['description'] ?? '',
                'order' => $order
            ]);
            
            \Log::info('Module created successfully', ['module_id' => $module->id]);
            
            return response()->json([
                'success' => true,
                'message' => 'Module created successfully',
                'module' => [
                    'id' => $module->id,
                    'title' => $module->title,
                    'description' => $module->description,
                    'order' => $module->order,
                    'lessons_count' => 0
                ]
            ]);
        } catch (\Illuminate\Auth\Access\AuthorizationException $e) {
            \Log::error('Authorization failed for module creation', [
                'course_id' => $course->id,
                'user_id' => auth()->id(),
                'error' => $e->getMessage()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'You are not authorized to modify this course.'
            ], 403);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed for module creation', [
                'errors' => $e->errors()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', $e->errors()['title'] ?? $e->errors())
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating module', [
                'course_id' => $course->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Error creating module: ' . $e->getMessage()
            ], 500);
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
            
            return response()->json([
                'success' => true,
                'message' => 'Module updated successfully',
                'module' => [
                    'id' => $module->id,
                    'title' => $module->title,
                    'description' => $module->description,
                    'order' => $module->order,
                    'lessons_count' => $module->lessons->count()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating module: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteModule(Course $course, $moduleId)
    {
        $this->authorize('update', $course);
        
        $module = $course->modules()->findOrFail($moduleId);
        
        try {
            $module->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Module deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting module: ' . $e->getMessage()
            ], 500);
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
            
            return response()->json([
                'success' => true,
                'message' => 'Modules reordered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reordering modules: ' . $e->getMessage()
            ], 500);
        }
    }
    
    // Lesson Management Methods  
    public function storeLesson(Request $request, Course $course, $moduleId)
    {
        $this->authorize('update', $course);
        
        $module = $course->modules()->findOrFail($moduleId);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => 'required|in:video,pdf,link',
            'description' => 'nullable|string|max:1000',
            'content_path' => 'nullable|string|max:500',
            'duration' => 'nullable|integer|min:0'
        ]);
        
        try {
            $order = $module->lessons()->max('order') + 1;
            
            $lesson = $module->lessons()->create([
                'title' => $validated['title'],
                'type' => $validated['type'],
                'description' => $validated['description'] ?? '',
                'content_path' => $validated['content_path'] ?? null,
                'order' => $order
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Lesson created successfully',
                'lesson' => [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'type' => $lesson->type,
                    'description' => $lesson->description,
                    'order' => $lesson->order,
                    'duration' => $lesson->duration ?? 0
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating lesson: ' . $e->getMessage()
            ], 500);
        }
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
            
            return response()->json([
                'success' => true,
                'message' => 'Lesson updated successfully',
                'lesson' => [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'type' => $lesson->type,
                    'description' => $lesson->description,
                    'order' => $lesson->order,
                    'duration' => $lesson->duration ?? 0
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating lesson: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function deleteLesson(Course $course, $moduleId, $lessonId)
    {
        $this->authorize('update', $course);
        
        $module = $course->modules()->findOrFail($moduleId);
        $lesson = $module->lessons()->findOrFail($lessonId);
        
        try {
            $lesson->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Lesson deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting lesson: ' . $e->getMessage()
            ], 500);
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
            
            return response()->json([
                'success' => true,
                'message' => 'Lessons reordered successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error reordering lessons: ' . $e->getMessage()
            ], 500);
        }
    }
}
