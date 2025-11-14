<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCourseRequest;
use App\Models\Course;
use App\Services\Course\CourseCategoryService;
use App\Services\Course\CourseService;
use App\Services\Instructors\InstructorStatsService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

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
        return view("instructors.courses.index", compact("courses", "instructorStats"));
    }

    public function create()
    {
        $categories = $this->courseCategoryService->fetchAll();
        return view("instructors.courses.create-course", compact("categories"));
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
        return view('instructors.courses.edit', compact('course', 'categories'));
    }

    public function update(CreateCourseRequest $request, Course $course)
    {
        $this->authorize('update', $course);
        
        try {
            // Handle file upload if provided
            $file = $request->file('image');
            $data = $request->validated();
            
            if ($file) {
                $course = $this->courseService->updateCourse($course, $data, $file);
            } else {
                $course = $this->courseService->updateCourse($course, $data);
            }
            
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
        
        // Load course with modules and lessons for the builder
        $course->load(['modules.lessons' => function($query) {
            $query->orderBy('order');
        }]);
        
        return view('instructors.courses.builder', compact('course'));
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
}
