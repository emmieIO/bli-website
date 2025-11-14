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
        return view("admin.courses.index", compact("categories", "courses"));
    }

    public function builder(Course $course)
    {
        $lessontypes = LessonType::options();
        return view("admin.courses.builder", compact("course", "lessontypes"));
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
    public function store(CreateCourseRequest $request)
    {
        $this->authorize('create', Course::class);
        $course = $this->courseService->createCourse($request->all(), $request->file('thumbnail_path'));
        if ($course) {
            return redirect()->back()->with([
                "message" => "Course created successfully",
                "type" => "success"
            ]);
        }
        return redirect()->back()->with([
            "message" => "Error creating course",
            "type" => "error"
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
    public function update(Request $request, Course $course)
    {
        $this->authorize('update', $course);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'level' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'thumbnail_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $updatedCourse = $this->courseService->updateCourse(
                $course, 
                $validated, 
                $request->file('thumbnail_path')
            );
            
            if ($updatedCourse) {
                return redirect()->back()->with([
                    "message" => "Course updated successfully",
                    "type" => "success"
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->back()->with([
                "message" => "Error updating course: " . $e->getMessage(),
                "type" => "error"
            ]);
        }

        return redirect()->back()->with([
            "message" => "Error updating course",
            "type" => "error"
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
