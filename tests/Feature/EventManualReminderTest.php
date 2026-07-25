<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Models\Event;
use App\Models\EventGuestAttendee;
use App\Models\User;
use App\Notifications\UpcomingEventReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class EventManualReminderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        app(PermissionRegistrar::class)->forgetCachedPermissions();
        Permission::findOrCreate(EventPermissionsEnum::SEND_UPDATES->value, 'web');
    }

    public function test_authorized_user_can_remind_only_confirmed_account_and_guest_registrations(): void
    {
        Notification::fake();

        $manager = User::factory()->create();
        $manager->givePermissionTo(EventPermissionsEnum::SEND_UPDATES->value);

        $event = Event::factory()->create([
            'creator_id' => $manager->id,
            'theme' => 'Beacon Summit',
        ]);
        $event->update([
            'metadata' => [
                ...($event->metadata ?? []),
                'meeting_link' => 'https://meet.google.com/beacon-live-room',
            ],
        ]);
        $confirmedUser = User::factory()->create();
        $cancelledUser = User::factory()->create();
        $attendedUser = User::factory()->create();

        $event->attendees()->attach($confirmedUser->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);
        $event->attendees()->attach($cancelledUser->id, [
            'status' => EventRegistrationStatus::CANCELLED->value,
            'revoke_count' => 1,
        ]);
        $event->attendees()->attach($attendedUser->id, [
            'status' => EventRegistrationStatus::ATTENDED->value,
            'revoke_count' => 0,
        ]);

        $confirmedGuest = EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'name' => 'Confirmed Guest',
            'email' => 'confirmed-guest@example.com',
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);
        $cancelledGuest = EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'name' => 'Cancelled Guest',
            'email' => 'cancelled-guest@example.com',
            'status' => EventRegistrationStatus::CANCELLED->value,
        ]);

        $response = $this->actingAs($manager)
            ->post(route('admin.events.send-reminder', $event));

        $response
            ->assertRedirect()
            ->assertSessionHas('type', 'success')
            ->assertSessionHas('message', 'Reminder queued for 2 confirmed attendees.');

        Notification::assertSentToTimes($confirmedUser, UpcomingEventReminder::class, 1);
        Notification::assertSentToTimes($confirmedGuest, UpcomingEventReminder::class, 1);
        Notification::assertSentTo(
            $confirmedUser,
            UpcomingEventReminder::class,
            fn (UpcomingEventReminder $notification) => $notification->actionUrl()
                === 'https://meet.google.com/beacon-live-room'
                && $notification->toMail($confirmedUser)->actionUrl
                === 'https://meet.google.com/beacon-live-room'
        );
        Notification::assertNotSentTo($cancelledUser, UpcomingEventReminder::class);
        Notification::assertNotSentTo($attendedUser, UpcomingEventReminder::class);
        Notification::assertNotSentTo($cancelledGuest, UpcomingEventReminder::class);
    }

    public function test_user_without_send_updates_permission_cannot_trigger_reminders(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $event = Event::factory()->create([
            'creator_id' => $user->id,
            'theme' => 'Beacon Summit',
        ]);
        $confirmedUser = User::factory()->create();

        $event->attendees()->attach($confirmedUser->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $this->actingAs($user)
            ->post(route('admin.events.send-reminder', $event))
            ->assertForbidden();

        Notification::assertNothingSent();
    }
}
