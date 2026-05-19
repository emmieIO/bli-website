<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\EventStatus;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminWaitlistManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutMiddleware([
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        foreach (EventPermissionsEnum::cases() as $permission) {
            Permission::findOrCreate($permission->value);
        }
    }

    public function test_admin_can_promote_waitlisted_attendee_when_capacity_exists(): void
    {
        $admin = User::factory()->create();
        $attendee = User::factory()->create();
        $event = $this->makeEvent([
            'attendee_slots' => 2,
        ]);

        $admin->givePermissionTo('event-manage-attendees');

        $event->attendees()->attach($attendee->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.events.attendees.promote-waitlist', [$event->slug, $attendee->id])
        );

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Waitlisted attendee promoted successfully.');

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $attendee->id,
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);
    }

    public function test_admin_cannot_promote_waitlisted_attendee_when_no_capacity_exists(): void
    {
        $admin = User::factory()->create();
        $registered = User::factory()->create();
        $waitlisted = User::factory()->create();
        $event = $this->makeEvent([
            'attendee_slots' => 1,
        ]);

        $admin->givePermissionTo('event-manage-attendees');

        $event->attendees()->attach($registered->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $event->attendees()->attach($waitlisted->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
        ]);

        $response = $this->actingAs($admin)->post(
            route('admin.events.attendees.promote-waitlist', [$event->slug, $waitlisted->id])
        );

        $response->assertRedirect();
        $response->assertSessionHas('message', 'No seat is currently available for promotion.');

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $waitlisted->id,
            'status' => EventRegistrationStatus::WAITLISTED->value,
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
