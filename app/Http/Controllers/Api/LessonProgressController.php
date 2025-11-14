<?php

namespace App\Http\Controllers\Api;

use App\Contracts\Services\LearningProgressServiceInterface;
use App\DTOs\LessonProgressData;
use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonProgressController extends Controller
{
    public function __construct(
        private LearningProgressServiceInterface $progressService
    ) {}

    /**
     * Update lesson progress for the authenticated user
     */
    public function updateProgress(Request $request, Lesson $lesson): JsonResponse
    {
        $request->validate([
            'current_time' => 'required|numeric|min:0',
            'duration' => 'nullable|numeric|min:0',
            'is_completed' => 'boolean'
        ]);

        $progressData = LessonProgressData::fromRequest($request->all());
        
        $this->progressService->updateLessonProgress(
            auth()->user(),
            $lesson,
            $progressData
        );

        return response()->json([
            'success' => true,
            'message' => 'Progress updated successfully'
        ]);
    }

    /**
     * Mark lesson as completed
     */
    public function markCompleted(Lesson $lesson): JsonResponse
    {
        $this->progressService->markLessonCompleted(auth()->user(), $lesson);

        return response()->json([
            'success' => true,
            'message' => 'Lesson marked as completed'
        ]);
    }

    /**
     * Get lesson progress for the authenticated user
     */
    public function getProgress(Lesson $lesson): JsonResponse
    {
        $isCompleted = $this->progressService->isLessonCompleted(auth()->user(), $lesson);

        return response()->json([
            'is_completed' => $isCompleted
        ]);
    }

    /**
     * Check if course is completed by the authenticated user
     */
    public function getCourseCompletion(Course $course): JsonResponse
    {
        $isCompleted = $this->progressService->isCourseCompleted(auth()->user(), $course);

        return response()->json([
            'isCompleted' => $isCompleted,
            'course_id' => $course->id
        ]);
    }
}
