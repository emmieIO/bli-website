<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use App\Services\Event\EventRegistrationService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventTransitionAuditTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirmed_registration_writes_transition_audit(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'attendee_slots' => 5,
        ]);

        $this->actingAs($user);

        $result = app(EventRegistrationService::class)->registerIfAvailable($event, $user->id);

        $this->assertSame(EventRegistrationStatus::REGISTERED, $result);

        $this->assertDatabaseHas('event_transition_audits', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'actor_user_id' => $user->id,
            'action' => 'registration_confirmed',
            'from_status' => null,
            'to_status' => EventRegistrationStatus::REGISTERED->value,
        ]);
    }

    public function test_rsvp_cancellation_writes_transition_audit(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent();

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $this->actingAs($user);

        $result = app(EventRegistrationService::class)->revokeRsvp($event->slug);

        $this->assertTrue($result);

        $this->assertDatabaseHas('event_transition_audits', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'actor_user_id' => $user->id,
            'action' => 'registration_cancelled',
            'from_status' => EventRegistrationStatus::REGISTERED->value,
            'to_status' => EventRegistrationStatus::CANCELLED->value,
        ]);
    }

    private function makeEvent(array $overrides = []): Event
    {
        $creator = User::factory()->create();

        return Event::factory()->create(array_merge([
            'theme' => 'Beacon Summit',
            'creator_id' => $creator->id,
            'status' => EventStatus::REGISTRATION_OPEN->value,
            'is_published' => true,
            'is_active' => true,
            'attendee_slots' => 100,
            'entry_fee' => 0,
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(6),
        ], $overrides));
    }
}
