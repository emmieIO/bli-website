<?php

namespace App\Enums;

enum EventRegistrationStatus: string
{
    case REGISTERED = 'registered';
    case WAITLISTED = 'waitlisted';
    case CANCELLED = 'cancelled';
    case ATTENDED = 'attended';
    case NO_SHOW = 'no_show';

    public const CONFIRMED = self::REGISTERED;

    public static function values(): array
    {
        return array_map(static fn (self $case) => $case->value, self::cases());
    }

    public static function fromValue(?string $value): ?self
    {
        if (! $value) {
            return null;
        }

        if ($value === 'confirmed') {
            return self::CONFIRMED;
        }

        return self::tryFrom($value);
    }

    public static function workspaceAccessibleValues(): array
    {
        return [
            self::REGISTERED->value,
            self::WAITLISTED->value,
            self::ATTENDED->value,
            self::NO_SHOW->value,
        ];
    }

    public static function seatOccupyingValues(): array
    {
        return [
            self::REGISTERED->value,
            self::ATTENDED->value,
            self::NO_SHOW->value,
        ];
    }

    public static function reminderEligibleValues(): array
    {
        return [
            self::REGISTERED->value,
        ];
    }

    public function occupiesSeat(): bool
    {
        return in_array($this, [
            self::REGISTERED,
            self::ATTENDED,
            self::NO_SHOW,
        ], true);
    }

    public function hasAttendeeWorkspace(): bool
    {
        return in_array($this, [
            self::REGISTERED,
            self::WAITLISTED,
            self::ATTENDED,
            self::NO_SHOW,
        ], true);
    }

    public function canReceiveReminders(): bool
    {
        return $this === self::REGISTERED;
    }

    public function canCancel(): bool
    {
        return in_array($this, [
            self::REGISTERED,
            self::WAITLISTED,
        ], true);
    }

    public function canTransitionTo(self $target): bool
    {
        return match ($this) {
            self::REGISTERED => in_array($target, [
                self::CANCELLED,
                self::ATTENDED,
                self::NO_SHOW,
            ], true),
            self::WAITLISTED => in_array($target, [
                self::REGISTERED,
                self::CANCELLED,
            ], true),
            self::CANCELLED => in_array($target, [
                self::REGISTERED,
                self::WAITLISTED,
            ], true),
            self::ATTENDED, self::NO_SHOW => false,
        };
    }

    public function label(): string
    {
        return match ($this) {
            self::REGISTERED => 'confirmed',
            self::WAITLISTED => 'waitlisted',
            self::CANCELLED => 'cancelled',
            self::ATTENDED => 'attended',
            self::NO_SHOW => 'no show',
        };
    }
}
