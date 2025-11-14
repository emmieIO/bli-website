<?php

namespace App\Services;

use App\Contracts\Repositories\CourseRepositoryInterface;
use App\Contracts\Repositories\LessonProgressRepositoryInterface;
use App\Contracts\Services\LearningProgressServiceInterface;
use App\DTOs\CourseProgressData;
use App\DTOs\LessonProgressData;
use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;

class LearningProgressService implements LearningProgressServiceInterface
{
    public function __construct(
        private LessonProgressRepositoryInterface $progressRepository,
        private CourseRepositoryInterface $courseRepository
    ) {}

    public function updateLessonProgress(User $user, Lesson $lesson, LessonProgressData $progressData): void
    {
        $this->progressRepository->updateOrCreateProgress($user, $lesson, [
            'last_position' => $progressData->currentTime,
            'watch_duration' => max($progressData->currentTime, 0),
            'is_completed' => $progressData->isCompleted,
        ]);
    }

    public function markLessonCompleted(User $user, Lesson $lesson): void
    {
        $this->progressRepository->markLessonCompleted($user, $lesson);
    }

    public function getCourseProgress(User $user, Course $course): CourseProgressData
    {
        // Load course with modules and lessons to get total count
        $course->load('modules.lessons');
        
        $totalLessons = 0;
        foreach ($course->modules as $module) {
            $totalLessons += $module->lessons->count();
        }

        $completedLessons = $this->progressRepository->getCompletedLessonsCount($user, $course);

        return CourseProgressData::create(
            totalLessons: $totalLessons,
            completedLessons: $completedLessons
        );
    }

    public function isLessonCompleted(User $user, Lesson $lesson): bool
    {
        $progress = $this->progressRepository->findUserLessonProgress($user, $lesson);
        
        return $progress?->is_completed ?? false;
    }

    public function isCourseCompleted(User $user, Course $course): bool
    {
        $progressData = $this->getCourseProgress($user, $course);
        
        return $progressData->isCompleted;
    }
}