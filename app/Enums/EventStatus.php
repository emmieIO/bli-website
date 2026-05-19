<?php

namespace App\Enums;

enum EventStatus: string
{
    case DRAFT = 'draft';
    case REVIEW = 'review';
    case PUBLISHED = 'published';
    case REGISTRATION_OPEN = 'registration_open';
    case REGISTRATION_CLOSED = 'registration_closed';
    case LIVE = 'live';
    case COMPLETED = 'completed';
    case CANCELLED = 'cancelled';
    case ARCHIVED = 'archived';

    public static function values(): array
    {
        return array_map(static fn (self $case) => $case->value, self::cases());
    }

    public static function fromLegacyFlags(bool $isPublished, bool $isActive): self
    {
        if (! $isPublished) {
            return self::DRAFT;
        }

        if (! $isActive) {
            return self::CANCELLED;
        }

        return self::PUBLISHED;
    }

    public static function publiclyVisibleValues(): array
    {
        return array_map(
            static fn (self $status) => $status->value,
            array_filter(self::cases(), static fn (self $status) => $status->isPubliclyVisible())
        );
    }

    public function isPubliclyVisible(): bool
    {
        return in_array($this, [
            self::PUBLISHED,
            self::REGISTRATION_OPEN,
            self::REGISTRATION_CLOSED,
            self::LIVE,
            self::COMPLETED,
        ], true);
    }

    public function allowsRegistration(): bool
    {
        return in_array($this, [
            self::REGISTRATION_OPEN,
        ], true);
    }

    public function isActive(): bool
    {
        return ! in_array($this, [
            self::CANCELLED,
            self::ARCHIVED,
        ], true);
    }

    public function usesPublishedFlag(): bool
    {
        return $this->isPubliclyVisible();
    }
}
