<?php

namespace App\Http\Controllers\Course;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseModule;
use App\Services\Course\CourseModuleService;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

class CourseModuleController extends Controller
{
    use AuthorizesRequests;

    public function __construct(protected CourseModuleService $courseModuleService) {}

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Course $course)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $this->courseModuleService->createModule($course, $validated);

            return redirect()->back()->with([
                'message' => 'Module created successfully',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Failed to create module: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course, CourseModule $module)
    {
        $this->authorize('update', $course);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        try {
            $this->courseModuleService->updateModule($module, $validated);

            return redirect()->back()->with([
                'message' => 'Module updated successfully',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Failed to update module: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course, CourseModule $module)
    {
        $this->authorize('update', $course);

        try {
            $this->courseModuleService->deleteModule($module);

            return redirect()->back()->with([
                'message' => 'Module deleted successfully',
                'type' => 'success',
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with([
                'message' => 'Failed to delete module: ' . $e->getMessage(),
                'type' => 'error',
            ]);
        }
    }
}
