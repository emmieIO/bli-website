<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Models\Event;
use App\Models\EventGuestAttendee;
use App\Models\User;
use App\Notifications\EventLiveNotification;
use App\Notifications\UpcomingEventReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
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
            'mode' => 'online',
            'location' => null,
            'physical_address' => null,
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

    public function test_manager_can_select_individual_confirmed_reminder_recipients(): void
    {
        Notification::fake();

        $manager = User::factory()->create();
        $manager->givePermissionTo(EventPermissionsEnum::SEND_UPDATES->value);
        $event = Event::factory()->create([
            'creator_id' => $manager->id,
            'theme' => 'Beacon Summit',
        ]);
        $selectedUser = User::factory()->create();
        $unselectedUser = User::factory()->create();
        $selectedGuest = EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'name' => 'Selected Guest',
            'email' => 'selected@example.com',
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);

        foreach ([$selectedUser, $unselectedUser] as $attendee) {
            $event->attendees()->attach($attendee->id, [
                'status' => EventRegistrationStatus::REGISTERED->value,
                'revoke_count' => 0,
            ]);
        }

        $this->actingAs($manager)->post(route('admin.events.send-reminder', $event), [
            'account_ids' => [$selectedUser->id],
            'guest_ids' => [$selectedGuest->id],
        ])->assertSessionHas('message', 'Reminder queued for 2 confirmed attendees.');

        Notification::assertSentToTimes($selectedUser, UpcomingEventReminder::class, 1);
        Notification::assertSentToTimes($selectedGuest, UpcomingEventReminder::class, 1);
        Notification::assertNotSentTo($unselectedUser, UpcomingEventReminder::class);
    }

    public function test_manager_can_send_live_alert_with_direct_and_guest_safe_access(): void
    {
        Notification::fake();

        $manager = User::factory()->create();
        $manager->givePermissionTo(EventPermissionsEnum::SEND_UPDATES->value);
        $event = Event::factory()->create([
            'creator_id' => $manager->id,
            'theme' => 'Beacon Summit',
            'status' => 'live',
            'mode' => 'online',
            'location' => 'https://meet.google.com/beacon-live-room',
        ]);
        $confirmedUser = User::factory()->create();
        $cancelledUser = User::factory()->create();
        $event->attendees()->attach($confirmedUser->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);
        $event->attendees()->attach($cancelledUser->id, [
            'status' => EventRegistrationStatus::CANCELLED->value,
            'revoke_count' => 1,
        ]);
        $confirmedGuest = EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'name' => 'Confirmed Guest',
            'email' => 'confirmed-guest@example.com',
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);
        $guestCode = null;

        $this->actingAs($manager)
            ->post(route('admin.events.send-live-alert', $event))
            ->assertSessionHas('message', 'Live alert queued for 2 confirmed attendees.');

        Notification::assertSentTo(
            $confirmedUser,
            EventLiveNotification::class,
            function (EventLiveNotification $notification) use ($confirmedUser): bool {
                $mail = $notification->toMail($confirmedUser);

                return $mail->actionUrl === 'https://meet.google.com/beacon-live-room'
                    && collect($mail->introLines)->contains(
                        fn ($line) => str_contains((string) $line, 'West Africa Time (WAT)')
                    );
            }
        );
        Notification::assertSentTo(
            $confirmedGuest,
            EventLiveNotification::class,
            function (EventLiveNotification $notification) use ($confirmedGuest, $event, &$guestCode): bool {
                $guestCode = $notification->guestAccessCode;

                return preg_match('/^\d{6}$/', $guestCode) === 1
                    && $notification->toMail($confirmedGuest)->actionUrl === route('events.show', $event->slug);
            }
        );
        Notification::assertNotSentTo($cancelledUser, EventLiveNotification::class);

        $confirmedGuest->refresh();
        $this->assertTrue(Hash::check($guestCode, $confirmedGuest->access_code_hash));
        $this->assertTrue($confirmedGuest->access_code_expires_at->isFuture());
        $this->assertSame('Africa/Lagos', config('app.timezone'));
    }

    public function test_live_alert_requires_live_status_and_a_meeting_link(): void
    {
        Notification::fake();

        $manager = User::factory()->create();
        $manager->givePermissionTo(EventPermissionsEnum::SEND_UPDATES->value);
        $event = Event::factory()->create([
            'creator_id' => $manager->id,
            'theme' => 'Beacon Summit',
            'status' => 'registration_open',
            'mode' => 'online',
            'location' => null,
        ]);

        $this->actingAs($manager)
            ->post(route('admin.events.send-live-alert', $event))
            ->assertSessionHas('message', 'Set the event status to Live before sending a live alert.');

        $event->update(['status' => 'live']);

        $this->actingAs($manager)
            ->post(route('admin.events.send-live-alert', $event))
            ->assertSessionHas('message', 'Add a meeting link before sending a live alert.');

        Notification::assertNothingSent();
    }
}
