<?php

namespace App\Enums;

enum CourseLevel : string
{
    case BEGINNER = 'beginner';
    case INTERMEDIATE = 'intermediate';
    case ADVANCED = 'advanced';

    public function label(): string
{
    return match($this) {
        CourseLevel::BEGINNER => 'Beginner',
        CourseLevel::INTERMEDIATE => 'Intermediate',
        CourseLevel::ADVANCED => 'Advanced',
    };
}
public static function options(): array
{
    return array_map(
        fn($level) => ['value' => $level->value, 'label' => $level->label()],
        self::cases()
    );
}
}

