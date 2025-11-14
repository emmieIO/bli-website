<?php

namespace App\DTOs;

readonly class LessonProgressData
{
    public function __construct(
        public float $currentTime,
        public ?float $duration = null,
        public bool $isCompleted = false,
        public ?int $watchDuration = null,
        public ?int $lastPosition = null
    ) {}

    public static function fromRequest(array $data): self
    {
        return new self(
            currentTime: (float) $data['current_time'],
            duration: isset($data['duration']) ? (float) $data['duration'] : null,
            isCompleted: (bool) ($data['is_completed'] ?? false),
            watchDuration: isset($data['watch_duration']) ? (int) $data['watch_duration'] : null,
            lastPosition: isset($data['last_position']) ? (int) $data['last_position'] : null
        );
    }

    public function toArray(): array
    {
        return [
            'current_time' => $this->currentTime,
            'duration' => $this->duration,
            'is_completed' => $this->isCompleted,
            'watch_duration' => $this->watchDuration,
            'last_position' => $this->lastPosition,
        ];
    }
}