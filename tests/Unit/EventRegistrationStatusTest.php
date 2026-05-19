<?php

use App\Enums\EventRegistrationStatus;

test('workspace accessible registration states are explicit', function () {
    expect(EventRegistrationStatus::workspaceAccessibleValues())
        ->toBe([
            EventRegistrationStatus::REGISTERED->value,
            EventRegistrationStatus::WAITLISTED->value,
            EventRegistrationStatus::ATTENDED->value,
            EventRegistrationStatus::NO_SHOW->value,
        ]);
});

test('seat occupancy registration states are explicit', function () {
    expect(EventRegistrationStatus::seatOccupyingValues())
        ->toBe([
            EventRegistrationStatus::REGISTERED->value,
            EventRegistrationStatus::ATTENDED->value,
            EventRegistrationStatus::NO_SHOW->value,
        ]);
});

test('event registration transitions are constrained', function () {
    expect(EventRegistrationStatus::REGISTERED->canTransitionTo(EventRegistrationStatus::WAITLISTED))->toBeFalse();
    expect(EventRegistrationStatus::REGISTERED->canTransitionTo(EventRegistrationStatus::CANCELLED))->toBeTrue();
    expect(EventRegistrationStatus::REGISTERED->canTransitionTo(EventRegistrationStatus::REFUNDED))->toBeTrue();
    expect(EventRegistrationStatus::WAITLISTED->canTransitionTo(EventRegistrationStatus::REGISTERED))->toBeTrue();
    expect(EventRegistrationStatus::WAITLISTED->canTransitionTo(EventRegistrationStatus::REFUNDED))->toBeTrue();
    expect(EventRegistrationStatus::WAITLISTED->canTransitionTo(EventRegistrationStatus::ATTENDED))->toBeFalse();
    expect(EventRegistrationStatus::CANCELLED->canTransitionTo(EventRegistrationStatus::REGISTERED))->toBeTrue();
    expect(EventRegistrationStatus::REFUNDED->canTransitionTo(EventRegistrationStatus::REGISTERED))->toBeFalse();
    expect(EventRegistrationStatus::ATTENDED->canTransitionTo(EventRegistrationStatus::REGISTERED))->toBeFalse();
});

test('confirmed compatibility resolves to the stored registered state', function () {
    expect(EventRegistrationStatus::fromValue('confirmed'))->toBe(EventRegistrationStatus::CONFIRMED);
    expect(EventRegistrationStatus::CONFIRMED->value)->toBe('registered');
    expect(EventRegistrationStatus::CONFIRMED->label())->toBe('confirmed');
});
