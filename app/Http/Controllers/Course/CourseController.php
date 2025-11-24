<?php

namespace App\Http\Controllers\Course;

use App\Enums\LessonType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Services\Course\CourseService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    use AuthorizesRequests;
    public function __construct(
        public CourseService $courseService
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Course::class);
        $categories = Category::all();
        $courses = $this->courseService->fetchCourses();
        return \Inertia\Inertia::render('Admin/Courses/Index', compact("categories", "courses"));
    }

    public function builder(Course $course)
    {
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

        return \Inertia\Inertia::render('Admin/Courses/Builder', compact('course'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Course::class);
        $categories = Category::all();
        $levels = \App\Enums\CourseLevel::options();
        return \Inertia\Inertia::render('Admin/Courses/Create', compact('categories', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCourseRequest $request)
    {
        $this->authorize('create', Course::class);
        $course = $this->courseService->createCourse($request->all(), $request->file('thumbnail'));
        if ($course) {
            return to_route('admin.courses.builder', $course)->with([
                "message" => "Course created successfully. Now add modules and lessons!",
                "type" => "success"
            ]);
        }
        return redirect()->back()->with([
            "message" => "Error creating course",
            "type" => "error"
        ]);
    }

    /**
     * Display the specified resource for public viewing
     */
    public function show(Course $course)
    {
        // Load relationships needed for the course detail page
        $course->load([
            'instructor',
            'modules.lessons',
            'outcomes',
            'requirements',
            'students'
        ]);
        
        return view('courses.course-detail', compact('course'));
    }

    /**
     * Course learning interface for enrolled students
     */
    public function learn(Course $course, $lesson = null)
    {
        // Check if user is enrolled in the course
        if (!auth()->user() || !$course->students()->where('user_id', auth()->id())->exists()) {
            return redirect()->route('courses.show', $course)
                ->with('error', 'You must be enrolled in this course to access the learning interface.');
        }

        // Load course with relationships
        $course->load([
            'modules.lessons' => function($query) {
                $query->orderBy('order');
            }
        ]);

        // Find current lesson
        $currentLesson = null;
        $previousLesson = null;
        $nextLesson = null;
        $allLessons = collect();

        // Flatten all lessons from all modules
        foreach($course->modules as $module) {
            foreach($module->lessons as $lessonItem) {
                $allLessons->push($lessonItem);
            }
        }

        if ($lesson) {
            $currentLesson = $allLessons->firstWhere('id', $lesson);
            
            if ($currentLesson) {
                $currentIndex = $allLessons->search(function($item) use ($currentLesson) {
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

        // Calculate progress
        $totalLessons = $allLessons->count();
        // Lesson completion tracking is handled via LessonProgress model
        $completedLessons = 0;
        $progress = $totalLessons > 0 ? round(($completedLessons / $totalLessons) * 100) : 0;

        return view('courses.learn', compact(
            'course',
            'currentLesson',
            'previousLesson', 
            'nextLesson',
            'progress',
            'totalLessons',
            'completedLessons'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        $this->authorize('update', $course);

        $categories = Category::all();
        $levels = \App\Enums\CourseLevel::options();
        return \Inertia\Inertia::render('Admin/Courses/Edit', compact('course', 'categories', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'language' => 'required|string',
            'level' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'is_free' => 'nullable',
            'price' => 'required|numeric|min:0',
            'thumbnail_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'preview_video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:51200'
        ]);

        // Convert is_free to boolean (handles string "true"/"false" from FormData)
        $validated['is_free'] = filter_var($validated['is_free'] ?? false, FILTER_VALIDATE_BOOLEAN);

        try {
            $this->courseService->updateCourse(
                $course,
                $validated,
                $request->file('thumbnail_path'),
                $request->file('preview_video')
            );

            return redirect()->route('admin.courses.index')->with([
                "message" => "Course updated successfully",
                "type" => "success"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with([
                "message" => "Error updating course: " . $e->getMessage(),
                "type" => "error"
            ]);
        }
    }

    /**
     * Approve a course
     */
    public function approve(Course $course)
    {
        $this->authorize('update', $course);

        try {
            // Update course status to approved
            $course->update(['status' => \App\Enums\ApplicationStatus::APPROVED]);

            // Notify the instructor
            $course->instructor->notify(new \App\Notifications\CourseApproved($course));

            return redirect()->back()->with([
                "message" => "Course approved successfully",
                "type" => "success"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "message" => "Error approving course: " . $e->getMessage(),
                "type" => "error"
            ]);
        }
    }

    /**
     * Reject a course
     */
    public function reject(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'rejection_reason' => 'nullable|string|max:1000'
        ]);

        try {
            // Update course status to rejected
            $course->update(['status' => \App\Enums\ApplicationStatus::REJECTED]);

            // Notify the instructor with rejection reason
            $course->instructor->notify(
                new \App\Notifications\CourseRejected($course, $validated['rejection_reason'] ?? null)
            );

            return redirect()->back()->with([
                "message" => "Course rejected successfully",
                "type" => "success"
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "message" => "Error rejecting course: " . $e->getMessage(),
                "type" => "error"
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $this->authorize('delete', $course);

        try {
            $this->courseService->deleteCourse($course);

            return redirect()->route('admin.courses.index')->with([
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
