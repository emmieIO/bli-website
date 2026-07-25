<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Models\Event;
use App\Models\EventGuestAttendee;
use App\Models\User;
use App\Notifications\EventGuestAccessCodeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class EventGuestAccessCodeResendTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Permission::findOrCreate(EventPermissionsEnum::MANAGE_ATTENDEES->value, 'web');
    }

    public function test_event_manager_can_resend_a_new_code_to_a_confirmed_guest(): void
    {
        Notification::fake();

        $manager = User::factory()->create();
        $manager->givePermissionTo(EventPermissionsEnum::MANAGE_ATTENDEES->value);
        $event = Event::factory()->create([
            'creator_id' => $manager->id,
            'theme' => 'Leadership and service',
        ]);
        $guest = EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'name' => 'Guest Attendee',
            'email' => 'guest@example.com',
            'status' => EventRegistrationStatus::REGISTERED->value,
            'access_code_hash' => Hash::make('111111'),
            'access_code_expires_at' => now()->addMinutes(5),
        ]);
        $newCode = null;

        $this->actingAs($manager)
            ->post(route('admin.events.guests.resend-access-code', [$event, $guest]))
            ->assertRedirect()
            ->assertSessionHas('type', 'success')
            ->assertSessionHas('message', 'A new access code was queued for guest@example.com.');

        Notification::assertSentTo(
            $guest,
            EventGuestAccessCodeNotification::class,
            function (EventGuestAccessCodeNotification $notification) use (&$newCode): bool {
                $newCode = $notification->accessCode;

                return preg_match('/^\d{6}$/', $newCode) === 1;
            }
        );

        $guest->refresh();
        $this->assertNotNull($newCode);
        $this->assertTrue(Hash::check($newCode, $guest->access_code_hash));
        $this->assertFalse(Hash::check('111111', $guest->access_code_hash));
        $this->assertTrue($guest->access_code_expires_at->isFuture());
    }

    public function test_user_without_attendee_permission_cannot_resend_guest_code(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create([
            'creator_id' => $user->id,
            'theme' => 'Leadership and service',
        ]);
        $guest = $this->createGuest($event);

        $this->actingAs($user)
            ->post(route('admin.events.guests.resend-access-code', [$event, $guest]))
            ->assertForbidden();

        Notification::assertNothingSent();
    }

    public function test_manager_cannot_resend_code_to_cancelled_or_other_event_guest(): void
    {
        Notification::fake();

        $manager = User::factory()->create();
        $manager->givePermissionTo(EventPermissionsEnum::MANAGE_ATTENDEES->value);
        $event = Event::factory()->create([
            'creator_id' => $manager->id,
            'theme' => 'Leadership and service',
        ]);
        $otherEvent = Event::factory()->create([
            'creator_id' => $manager->id,
            'theme' => 'Character and competence',
        ]);
        $cancelledGuest = $this->createGuest($event, EventRegistrationStatus::CANCELLED);
        $otherGuest = $this->createGuest($otherEvent);

        $this->actingAs($manager)
            ->post(route('admin.events.guests.resend-access-code', [$event, $cancelledGuest]))
            ->assertSessionHas('type', 'error');

        $this->actingAs($manager)
            ->post(route('admin.events.guests.resend-access-code', [$event, $otherGuest]))
            ->assertNotFound();

        Notification::assertNothingSent();
    }

    private function createGuest(
        Event $event,
        EventRegistrationStatus $status = EventRegistrationStatus::REGISTERED
    ): EventGuestAttendee {
        return EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'name' => 'Guest Attendee',
            'email' => fake()->unique()->safeEmail(),
            'status' => $status->value,
        ]);
    }
}
