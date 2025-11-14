<?php

namespace App\Contracts\Services;

use App\Models\Course;
use App\Models\Lesson;
use App\Models\User;
use App\DTOs\LessonProgressData;
use App\DTOs\CourseProgressData;

interface LearningProgressServiceInterface
{
    public function updateLessonProgress(User $user, Lesson $lesson, LessonProgressData $progressData): void;
    
    public function markLessonCompleted(User $user, Lesson $lesson): void;
    
    public function getCourseProgress(User $user, Course $course): CourseProgressData;
    
    public function isLessonCompleted(User $user, Lesson $lesson): bool;
    
    public function isCourseCompleted(User $user, Course $course): bool;
}