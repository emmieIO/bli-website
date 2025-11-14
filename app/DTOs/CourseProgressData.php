<?php

namespace App\DTOs;

readonly class CourseProgressData
{
    public function __construct(
        public int $totalLessons,
        public int $completedLessons,
        public float $progressPercentage,
        public bool $isCompleted,
        public ?int $timeSpent = null,
        public ?string $lastAccessedAt = null
    ) {}

    public static function create(
        int $totalLessons,
        int $completedLessons,
        ?int $timeSpent = null,
        ?string $lastAccessedAt = null
    ): self {
        $progressPercentage = $totalLessons > 0 
            ? round(($completedLessons / $totalLessons) * 100, 2) 
            : 0.0;

        return new self(
            totalLessons: $totalLessons,
            completedLessons: $completedLessons,
            progressPercentage: $progressPercentage,
            isCompleted: $completedLessons === $totalLessons && $totalLessons > 0,
            timeSpent: $timeSpent,
            lastAccessedAt: $lastAccessedAt
        );
    }

    public function toArray(): array
    {
        return [
            'total_lessons' => $this->totalLessons,
            'completed_lessons' => $this->completedLessons,
            'progress_percentage' => $this->progressPercentage,
            'is_completed' => $this->isCompleted,
            'time_spent' => $this->timeSpent,
            'last_accessed_at' => $this->lastAccessedAt,
        ];
    }
}