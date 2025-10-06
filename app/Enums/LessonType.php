<?php

namespace App\Enums;

enum LessonType : string
{
    case VIDEO = 'video';
    case PDF = 'pdf';
    Case LINK = 'link';

    public function label(): string
    {
        return match($this) {
            LessonType::VIDEO => 'Video',
            LessonType::PDF => 'PDF Document',
            LessonType::LINK => 'External Link',
        };
    }


    public static function options(): array
    {
        return array_map(fn($case) => ["label"=>$case->label(), "value" => $case->value], self::cases());
    }
}
