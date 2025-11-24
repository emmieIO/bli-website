<?php

namespace App\Http\Controllers\Course;

use App\Enums\LessonType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateLessonRequest;
use App\Models\CourseModule;
use App\Models\Lesson;
use App\Services\Course\LessonService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LessonController extends Controller
{
    use AuthorizesRequests;

    public function __construct(
        protected LessonService $lessonService
    ) {}

    /**
     * Show the form for creating a new resource.
     */
    public function create(CourseModule $module)
    {
        $this->authorize('update', $module->course);

        $lessontypes = LessonType::options();

        return Inertia::render('Admin/Courses/AddLesson', [
            'lessontypes' => $lessontypes,
            'module' => $module,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateLessonRequest $request, CourseModule $module)
    {
        $this->authorize('update', $module->course);

        try {
            $this->lessonService->createLesson(
                $module,
                $request->validated(),
                $request->file('content_path'),
                $request->file('video_field')
            );

            return redirect()->back()->with([
                'message' => 'Lesson created successfully',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Failed to create lesson: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CourseModule $module, Lesson $lesson)
    {
        $this->authorize('update', $module->course);

        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'link_url' => 'nullable|url',
            ]);

            $this->lessonService->updateLesson(
                $lesson,
                $validated,
                $request->file('content_path'),
                $request->file('video_field')
            );

            return redirect()->back()->with([
                'message' => 'Lesson updated successfully',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Failed to update lesson: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CourseModule $module, Lesson $lesson)
    {
        $this->authorize('update', $module->course);

        try {
            $this->lessonService->deleteLesson($lesson);

            return redirect()->back()->with([
                'message' => 'Lesson deleted successfully',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Failed to delete lesson: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }

    /**
     * Reorder lessons within a module.
     */
    public function reorder(Request $request, CourseModule $module)
    {
        $this->authorize('update', $module->course);

        $validated = $request->validate([
            'lesson_order' => 'required|array',
            'lesson_order.*' => 'required|integer|exists:lessons,id',
        ]);

        try {
            $this->lessonService->reorderLessons($module, $validated['lesson_order']);

            return redirect()->back()->with([
                'message' => 'Lessons reordered successfully',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Failed to reorder lessons: ' . $e->getMessage(),
                'type' => 'error'
            ]);
        }
    }
}
