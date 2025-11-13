<?php

namespace App\Http\Controllers\Course;

use App\Enums\LessonType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLessonRequest;
use App\Models\CourseModule;
use App\Services\Course\CourseModuleService;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function __construct(
        public CourseModuleService $courseModuleService,
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(CourseModule $module)
    {

        $lessontypes = LessonType::options();
        return view("admin.courses.addLesson", compact("lessontypes", "module"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLessonRequest $request, CourseModule $module)
    {
        // dd($request->validated());
        $content_path = $request->file('content_path') ?? null;
        $video_field = $request->file('video_field') ?? null;
        $course = $this->courseModuleService->createModuleLesson($module, $request->validated(), $content_path, $video_field);

        return redirect()->back()->with([
            'message' => $course ? 'Lesson created successfully' : 'Failed to create lesson',
            'type' => $course ? 'success' : 'error'
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
