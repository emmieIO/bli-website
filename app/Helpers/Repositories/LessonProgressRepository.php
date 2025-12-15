<?php

namespace App\Repositories;

use App\Contracts\Repositories\LessonProgressRepositoryInterface;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class LessonProgressRepository implements LessonProgressRepositoryInterface
{
    public function findUserLessonProgress(User $user, Lesson $lesson): ?LessonProgress
    {
        return LessonProgress::where([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id
        ])->first();
    }

    public function updateOrCreateProgress(User $user, Lesson $lesson, array $data): LessonProgress
    {
        return LessonProgress::updateOrCreate(
            [
                'user_id' => $user->id,
                'lesson_id' => $lesson->id,
                'course_id' => $lesson->courseModule->course_id
            ],
            array_merge($data, [
                'completed_at' => $data['is_completed'] ?? false ? now() : null
            ])
        );
    }

    public function markLessonCompleted(User $user, Lesson $lesson): LessonProgress
    {
        return $this->updateOrCreateProgress($user, $lesson, [
            'is_completed' => true,
            'completed_at' => now()
        ]);
    }

    public function getUserCourseProgress(User $user, Course $course): Collection
    {
        return LessonProgress::where([
            'user_id' => $user->id,
            'course_id' => $course->id
        ])->with('lesson')->get();
    }

    public function getCompletedLessonsCount(User $user, Course $course): int
    {
        return LessonProgress::where([
            'user_id' => $user->id,
            'course_id' => $course->id,
            'is_completed' => true
        ])->count();
    }

    public function calculateCourseProgress(User $user, Course $course): float
    {
        // Load course with modules and lessons to get total count
        $course->load('modules.lessons');
        
        $totalLessons = 0;
        foreach ($course->modules as $module) {
            $totalLessons += $module->lessons->count();
        }

        if ($totalLessons === 0) {
            return 0.0;
        }

        $completedLessons = $this->getCompletedLessonsCount($user, $course);
        
        return round(($completedLessons / $totalLessons) * 100, 2);
    }
}