<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\User;
use App\Services\Event\PublicEventCtaResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicEventCtaResolverTest extends TestCase
{
    use RefreshDatabase;

    public function test_registered_attendee_gets_workspace_cta(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent();
        $event->attendees()->attach($user->id, ['status' => EventRegistrationStatus::REGISTERED->value]);

        $cta = app(PublicEventCtaResolver::class)->resolve($event->fresh(), $user);

        $this->assertSame('view_attendee_workspace', $cta['key']);
    }

    public function test_assigned_speaker_gets_workspace_cta(): void
    {
        $user = User::factory()->create();
        $speaker = Speaker::query()->create([
            'user_id' => $user->id,
            'created_by' => $user->id,
            'title' => 'Keynote Speaker',
            'organization' => 'Beacon Leadership Institute',
            'bio' => 'Experienced facilitator.',
            'status' => 'active',
        ]);
        $event = $this->makeEvent([
            'is_allowing_application' => true,
        ]);
        $event->speakers()->attach($speaker->id);

        $cta = app(PublicEventCtaResolver::class)->resolve($event->fresh(), $user->fresh());

        $this->assertSame('view_speaker_workspace', $cta['key']);
    }

    public function test_full_event_stops_registration(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'attendee_slots' => 0,
        ]);

        $cta = app(PublicEventCtaResolver::class)->resolve($event, $user);

        $this->assertSame('event_full', $cta['key']);
        $this->assertSame('status', $cta['kind']);
    }

    public function test_authenticated_user_gets_free_registration_cta(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'entry_fee' => 0,
            'attendee_slots' => 10,
        ]);

        $cta = app(PublicEventCtaResolver::class)->resolve($event, $user);

        $this->assertSame('register_now', $cta['key']);
        $this->assertSame('Register now', $cta['label']);
        $this->assertSame('post', $cta['method']);
        $this->assertTrue($cta['requires_confirmation']);
    }

    public function test_guest_gets_login_to_register_for_free_event(): void
    {
        $event = $this->makeEvent([
            'entry_fee' => 0,
            'attendee_slots' => 10,
        ]);

        $cta = app(PublicEventCtaResolver::class)->resolve($event, null);

        $this->assertSame('register_now', $cta['key']);
        $this->assertSame('Log in to register', $cta['label']);
        $this->assertTrue($cta['requires_auth']);
    }

    public function test_guest_can_register_with_email_when_signup_is_not_required_for_free_event(): void
    {
        $event = $this->makeEvent([
            'entry_fee' => 0,
            'attendee_slots' => 10,
            'require_sign_up' => false,
        ]);

        $cta = app(PublicEventCtaResolver::class)->resolve($event, null);

        $this->assertSame('register_now', $cta['key']);
        $this->assertSame('Register with email', $cta['label']);
        $this->assertSame('post', $cta['method']);
        $this->assertTrue($cta['requires_confirmation']);
        $this->assertTrue($cta['requires_email']);
        $this->assertFalse($cta['requires_auth']);
    }

    public function test_paid_event_prefers_buy_ticket(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'entry_fee' => 15000,
            'attendee_slots' => 10,
        ]);

        $cta = app(PublicEventCtaResolver::class)->resolve($event, $user);

        $this->assertSame('buy_ticket', $cta['key']);
    }

    public function test_speaking_application_can_be_primary_when_registration_is_unavailable(): void
    {
        $event = $this->makeEvent([
            'status' => EventStatus::PUBLISHED->value,
            'is_allowing_application' => true,
        ]);

        $cta = app(PublicEventCtaResolver::class)->resolve($event, null);

        $this->assertSame('apply_to_speak', $cta['key']);
    }

    public function test_cancelled_event_blocks_other_ctas(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'status' => EventStatus::CANCELLED->value,
        ]);

        $cta = app(PublicEventCtaResolver::class)->resolve($event, $user);

        $this->assertSame('cancelled', $cta['key']);
        $this->assertSame('status', $cta['kind']);
    }

    private function makeEvent(array $overrides = []): Event
    {
        $creator = User::factory()->create();

        return Event::factory()->create(array_merge([
            'theme' => 'Leadership Summit',
            'creator_id' => $creator->id,
            'status' => EventStatus::REGISTRATION_OPEN->value,
            'is_published' => true,
            'is_active' => true,
            'start_date' => now()->addWeek(),
            'end_date' => now()->addWeeks(2),
            'attendee_slots' => 100,
            'entry_fee' => 0,
            'is_allowing_application' => false,
        ], $overrides));
    }
}
