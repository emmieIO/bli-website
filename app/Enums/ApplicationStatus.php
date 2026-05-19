<?php

namespace App\Enums;

enum ApplicationStatus : string
{
    case DRAFT = 'draft';
    case PENDING = 'pending';
    case UNDER_REVIEW = 'under_review';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function fromValue(?string $value): ?self
    {
        return $value ? self::tryFrom($value) : null;
    }
}
