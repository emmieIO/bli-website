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
     * Show the form for creating a new resource.
     */
    public function create(CourseModule $module)
    {
        // Authorize: Only course owner or admin can create lessons
        $this->authorize('update', $module->course);

        $lessontypes = LessonType::options();
        return view("admin.courses.addLesson", compact("lessontypes", "module"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLessonRequest $request, CourseModule $module)
    {
        // Authorize: Only course owner or admin can create lessons
        $this->authorize('update', $module->course);

        // dd($request->validated());
        $content_path = $request->file('content_path') ?? null;
        $video_field = $request->file('video_field') ?? null;
        $course = $this->courseModuleService->createModuleLesson($module, $request->validated(), $content_path, $video_field);

        return redirect()->back()->with([
            'message' => $course ? 'Lesson created successfully' : 'Failed to create lesson',
            'type' => $course ? 'success' : 'error'
        ]);
    }

}
