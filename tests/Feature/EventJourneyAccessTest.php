<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\SpeakerInvite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventJourneyAccessTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);
    }

    public function test_registered_attendee_can_open_event_workspace(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent();

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $response = $this->actingAs($user)->get(route('user.events.show', $event->slug));

        $response->assertOk();
    }

    public function test_waitlisted_attendee_can_open_event_workspace(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent();

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
        ]);

        $response = $this->actingAs($user)->get(route('user.events.show', $event->slug));

        $response->assertOk();
    }

    public function test_cancelled_attendee_cannot_open_event_workspace(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent();

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::CANCELLED->value,
            'revoke_count' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('user.events.show', $event->slug));

        $response->assertNotFound();
    }

    public function test_speaker_workspace_requires_real_speaker_context(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'is_allowing_application' => true,
        ]);

        $response = $this->actingAs($user)->get(route('speaker.events.show', $event->slug));

        $response->assertNotFound();
    }

    public function test_speaker_workspace_opens_for_invited_speaker(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'is_allowing_application' => true,
        ]);
        $speaker = Speaker::query()->create([
            'user_id' => $user->id,
            'created_by' => $user->id,
            'title' => 'Leadership Coach',
            'organization' => 'Beacon Leadership Institute',
            'bio' => 'Speaker bio',
            'status' => 'active',
        ]);

        SpeakerInvite::query()->create([
            'event_id' => $event->id,
            'speaker_id' => $speaker->id,
            'status' => 'pending',
            'expires_at' => now()->addDay(),
        ]);

        $response = $this->actingAs($user)->get(route('speaker.events.show', $event->slug));

        $response->assertOk();
    }

    public function test_join_event_rejects_closed_registration(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'status' => EventStatus::PUBLISHED->value,
        ]);

        $response = $this->actingAs($user)
            ->from(route('events.show', $event->slug))
            ->post(route('events.join', $event->slug));

        $response
            ->assertRedirect(route('events.show', $event->slug))
            ->assertSessionHas('message', 'Registration is not open for this event.');

        $this->assertDatabaseMissing('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_free_event_join_confirms_attendee_and_redirects_to_workspace(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'entry_fee' => 0,
            'attendee_slots' => 5,
        ]);

        $response = $this->actingAs($user)
            ->from(route('events.show', $event->slug))
            ->post(route('events.join', $event->slug));

        $response
            ->assertRedirect(route('user.events.show', $event->slug))
            ->assertSessionHas('message', 'Your event registration has been confirmed.');

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);
    }

    public function test_full_free_event_join_waitlists_attendee_and_redirects_to_workspace(): void
    {
        $user = User::factory()->create();
        $otherAttendee = User::factory()->create();
        $event = $this->makeEvent([
            'entry_fee' => 0,
            'attendee_slots' => 1,
        ]);

        $event->attendees()->attach($otherAttendee->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $response = $this->actingAs($user)
            ->from(route('events.show', $event->slug))
            ->post(route('events.join', $event->slug));

        $response
            ->assertRedirect(route('user.events.show', $event->slug))
            ->assertSessionHas('message', 'This event is full right now. You have been added to the waitlist.');

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
        ]);
    }

    public function test_join_event_rejects_user_who_has_reached_revoke_limit(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent();

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::CANCELLED->value,
            'revoke_count' => 4,
        ]);

        $response = $this->actingAs($user)
            ->from(route('events.show', $event->slug))
            ->post(route('events.join', $event->slug));

        $response
            ->assertRedirect(route('events.show', $event->slug))
            ->assertSessionHas('message', 'Registration failed. The event has reached its maximum number of registrations.');

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => EventRegistrationStatus::CANCELLED->value,
            'revoke_count' => 4,
        ]);
    }

    public function test_cancelled_attendee_below_revoke_limit_can_rejoin_and_be_confirmed(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'attendee_slots' => 3,
        ]);

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::CANCELLED->value,
            'revoke_count' => 2,
        ]);

        $response = $this->actingAs($user)
            ->from(route('events.show', $event->slug))
            ->post(route('events.join', $event->slug));

        $response
            ->assertRedirect(route('user.events.show', $event->slug))
            ->assertSessionHas('message', 'Your event registration has been confirmed.');

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 2,
        ]);
    }

    public function test_cancelled_attendee_below_revoke_limit_can_rejoin_and_be_waitlisted_when_event_is_full(): void
    {
        $user = User::factory()->create();
        $otherAttendee = User::factory()->create();
        $event = $this->makeEvent([
            'attendee_slots' => 1,
        ]);

        $event->attendees()->attach($otherAttendee->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::CANCELLED->value,
            'revoke_count' => 1,
        ]);

        $response = $this->actingAs($user)
            ->from(route('events.show', $event->slug))
            ->post(route('events.join', $event->slug));

        $response
            ->assertRedirect(route('user.events.show', $event->slug))
            ->assertSessionHas('message', 'This event is full right now. You have been added to the waitlist.');

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 1,
        ]);
    }

    public function test_cannot_revoke_rsvp_from_cancelled_registration(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent();

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::CANCELLED->value,
            'revoke_count' => 1,
        ]);

        $response = $this->actingAs($user)
            ->from(route('user.events.show', $event->slug))
            ->delete(route('user.revoke.event', $event->slug));

        $response
            ->assertRedirect(route('user.events.show', $event->slug))
            ->assertSessionHas('message', 'Failed to revoke your RSVP. Please try again.');
    }

    public function test_registered_attendee_cancellation_auto_promotes_oldest_waitlisted_attendee(): void
    {
        $registeredUser = User::factory()->create();
        $firstWaitlistedUser = User::factory()->create();
        $secondWaitlistedUser = User::factory()->create();
        $event = $this->makeEvent([
            'attendee_slots' => 1,
        ]);

        $event->attendees()->attach($registeredUser->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
            'created_at' => now()->subHours(3),
            'updated_at' => now()->subHours(3),
        ]);

        $event->attendees()->attach($firstWaitlistedUser->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        $event->attendees()->attach($secondWaitlistedUser->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
        ]);

        $response = $this->actingAs($registeredUser)
            ->from(route('user.events.show', $event->slug))
            ->delete(route('user.revoke.event', $event->slug));

        $response
            ->assertRedirect(route('user.events.show', $event->slug))
            ->assertSessionHas('message', 'Your RSVP has been successfully revoked.');

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $registeredUser->id,
            'status' => EventRegistrationStatus::CANCELLED->value,
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $firstWaitlistedUser->id,
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $secondWaitlistedUser->id,
            'status' => EventRegistrationStatus::WAITLISTED->value,
        ]);
    }

    public function test_waitlisted_attendee_cancellation_does_not_promote_anyone(): void
    {
        $waitlistedUser = User::factory()->create();
        $otherWaitlistedUser = User::factory()->create();
        $event = $this->makeEvent([
            'attendee_slots' => 1,
        ]);

        $event->attendees()->attach($waitlistedUser->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
            'created_at' => now()->subHours(2),
            'updated_at' => now()->subHours(2),
        ]);

        $event->attendees()->attach($otherWaitlistedUser->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
        ]);

        $response = $this->actingAs($waitlistedUser)
            ->from(route('user.events.show', $event->slug))
            ->delete(route('user.revoke.event', $event->slug));

        $response
            ->assertRedirect(route('user.events.show', $event->slug))
            ->assertSessionHas('message', 'Your RSVP has been successfully revoked.');

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $waitlistedUser->id,
            'status' => EventRegistrationStatus::CANCELLED->value,
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $otherWaitlistedUser->id,
            'status' => EventRegistrationStatus::WAITLISTED->value,
        ]);
    }

    public function test_public_event_page_exposes_discipleship_program_profile(): void
    {
        $event = $this->makeEvent([
            'metadata' => [
                'program_type' => 'discipleship_track',
                'program_code' => 'BDT',
                'registration_mode' => 'selective',
                'requires_screening' => true,
                'cohort_duration_weeks' => 6,
                'weekly_prayer_target_minutes' => 420,
                'weekly_evangelism_target_min' => 3,
                'weekly_evangelism_target_max' => 5,
            ],
        ]);

        $response = $this->get(route('events.show', $event->slug));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Events/Show')
            ->where('event.program_profile.program_type', 'discipleship_track')
            ->where('event.program_profile.program_code', 'BDT')
            ->where('event.program_profile.registration_mode', 'selective')
            ->where('event.program_profile.requires_screening', true)
            ->where('event.program_profile.cohort_duration_weeks', 6)
            ->where('event.program_profile.weekly_prayer_target_minutes', 420));
    }

    public function test_attendee_workspace_exposes_discipleship_program_profile(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'metadata' => [
                'program_type' => 'discipleship_track',
                'program_code' => 'BDT',
                'group_model' => 'Cluster-based accountability groups',
                'central_teaching_schedule' => 'Weekly central teaching',
                'group_meeting_schedule' => 'Weekly reflection meetings',
                'weekly_discipleship_target_min' => 1,
                'weekly_discipleship_target_max' => 3,
            ],
        ]);

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $response = $this->actingAs($user)->get(route('user.events.show', $event->slug));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('MyEvents/Show')
            ->where('event.program_profile.program_type', 'discipleship_track')
            ->where('event.program_profile.program_code', 'BDT')
            ->where('event.program_profile.group_model', 'Cluster-based accountability groups')
            ->where('event.program_profile.central_teaching_schedule', 'Weekly central teaching')
            ->where('event.program_profile.group_meeting_schedule', 'Weekly reflection meetings')
            ->where('event.program_profile.weekly_discipleship_target_min', 1)
            ->where('event.program_profile.weekly_discipleship_target_max', 3));
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
