<?php

namespace App\Contracts\Repositories;

use App\Models\Lesson;
use App\Models\LessonProgress;
use App\Models\User;
use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;

interface LessonProgressRepositoryInterface
{
    public function findUserLessonProgress(User $user, Lesson $lesson): ?LessonProgress;
    
    public function updateOrCreateProgress(User $user, Lesson $lesson, array $data): LessonProgress;
    
    public function markLessonCompleted(User $user, Lesson $lesson): LessonProgress;
    
    public function getUserCourseProgress(User $user, Course $course): Collection;
    
    public function getCompletedLessonsCount(User $user, Course $course): int;
    
    public function calculateCourseProgress(User $user, Course $course): float;
}