<?php

namespace App\Enums;

enum SessionFormat : string
{
    case Presentation = 'presentation';
    case Panel = 'panel';
    case Workshop = 'workshop';
    case LightningTalk = 'lightning_talk';

    public function displayName(): string
    {
        return match ($this) {
            self::Presentation => 'Presentation',
            self::Panel => 'Panel Discussion',
            self::Workshop => 'Interactive Workshop',
            self::LightningTalk => 'Lightning Talk',
        };
    }

    // public static function values(): array
    // {
    //     return array_column(self::cases(), 'value');
    // }
}
