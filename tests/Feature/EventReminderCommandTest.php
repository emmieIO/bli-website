<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\EventGuestAttendee;
use App\Models\User;
use App\Notifications\UpcomingEventReminder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EventReminderCommandTest extends TestCase
{
    use RefreshDatabase;

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        Cache::flush();

        parent::tearDown();
    }

    public function test_reminder_command_only_notifies_confirmed_attendees_in_the_24_hour_window(): void
    {
        Notification::fake();
        Cache::flush();

        Carbon::setTestNow('2026-05-01 10:00:00');

        $creator = User::factory()->create();
        $confirmedUser = User::factory()->create();
        $cancelledUser = User::factory()->create();

        $event = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Beacon Summit',
            'status' => EventStatus::REGISTRATION_OPEN->value,
            'start_date' => Carbon::now()->addHours(24),
            'end_date' => Carbon::now()->addHours(26),
        ]);

        $event->attendees()->attach($confirmedUser->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $event->attendees()->attach($cancelledUser->id, [
            'status' => EventRegistrationStatus::CANCELLED->value,
            'revoke_count' => 1,
        ]);

        $this->artisan('app:send-event-reminders')->assertExitCode(0);

        Notification::assertSentToTimes($confirmedUser, UpcomingEventReminder::class, 1);
        Notification::assertNotSentTo($cancelledUser, UpcomingEventReminder::class);
    }

    public function test_reminder_command_does_not_duplicate_notifications_when_cache_lock_exists(): void
    {
        Notification::fake();
        Cache::flush();

        Carbon::setTestNow('2026-05-01 10:00:00');

        $creator = User::factory()->create();
        $confirmedUser = User::factory()->create();

        $event = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Beacon Summit',
            'status' => EventStatus::REGISTRATION_OPEN->value,
            'start_date' => Carbon::now()->addHours(2),
            'end_date' => Carbon::now()->addHours(4),
        ]);

        $event->attendees()->attach($confirmedUser->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $this->artisan('app:send-event-reminders')->assertExitCode(0);
        $this->artisan('app:send-event-reminders')->assertExitCode(0);

        Notification::assertSentToTimes($confirmedUser, UpcomingEventReminder::class, 1);
    }

    public function test_reminder_command_notifies_confirmed_guest_attendees(): void
    {
        Notification::fake();
        Cache::flush();

        Carbon::setTestNow('2026-05-01 10:00:00');

        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Beacon Summit',
            'status' => EventStatus::REGISTRATION_OPEN->value,
            'require_sign_up' => false,
            'start_date' => Carbon::now()->addHours(24),
            'end_date' => Carbon::now()->addHours(26),
        ]);

        $confirmedGuest = EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'email' => 'guest@example.com',
            'name' => 'Guest Attendee',
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);

        $this->artisan('app:send-event-reminders')->assertExitCode(0);

        Notification::assertSentToTimes($confirmedGuest, UpcomingEventReminder::class, 1);
    }

    public function test_reminder_command_schedules_each_detailed_program_day(): void
    {
        Notification::fake();
        Carbon::setTestNow('2026-05-01 10:00:00');

        $creator = User::factory()->create();
        $attendee = User::factory()->create();
        $event = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Seven Day Intensive',
            'start_date' => now()->addHours(24),
            'end_date' => now()->addDays(7),
        ]);
        $event->attendees()->attach($attendee->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);
        $day = $event->days()->create([
            'position' => 1,
            'title' => 'Character Lab',
            'theme' => 'Character',
            'start_at' => now()->addHours(24),
            'end_at' => now()->addHours(26),
            'mode' => 'online',
            'meeting_link' => 'https://meet.example.com/character-lab',
        ]);

        $this->artisan('app:send-event-reminders')->assertExitCode(0);

        Notification::assertSentTo(
            $attendee,
            UpcomingEventReminder::class,
            fn (UpcomingEventReminder $notification) => $notification->eventDay?->is($day)
                && $notification->actionUrl() === 'https://meet.example.com/character-lab'
        );
        Notification::assertSentToTimes($attendee, UpcomingEventReminder::class, 1);
    }

    public function test_reminder_notification_uses_human_readable_time_until_event(): void
    {
        Carbon::setTestNow('2026-05-01 10:00:00');

        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Beacon Summit',
            'start_date' => Carbon::now()->addHours(2)->addMinutes(5),
            'end_date' => Carbon::now()->addHours(4),
        ]);
        $user = User::factory()->create();

        $payload = (new UpcomingEventReminder($event))->toArray($user);

        $this->assertSame('in 2 hours', $payload['time_until']);
        $this->assertSame("Reminder: {$event->title} is starting in 2 hours!", $payload['message']);
    }

    public function test_reminder_falls_back_to_event_page_without_a_meeting_link(): void
    {
        $event = Event::factory()->create([
            'creator_id' => User::factory()->create()->id,
            'theme' => 'Beacon Summit',
            'mode' => 'online',
            'location' => null,
            'physical_address' => null,
            'metadata' => [],
        ]);

        $notification = new UpcomingEventReminder($event);

        $this->assertSame(route('events.show', $event->slug), $notification->actionUrl());
        $this->assertSame('View Event Details', $notification->toMail(User::factory()->create())->actionText);
    }

    public function test_reminder_uses_updated_default_link_before_stale_metadata_for_accounts_and_guests(): void
    {
        $event = Event::factory()->create([
            'creator_id' => User::factory()->create()->id,
            'theme' => 'Beacon Summit',
            'mode' => 'online',
            'location' => 'https://meet.example.com/updated-room',
            'physical_address' => null,
            'metadata' => [
                'meeting_link' => 'https://meet.example.com/stale-room',
            ],
        ]);
        $account = User::factory()->create();
        $guest = EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'email' => 'guest@example.com',
            'name' => 'Guest Attendee',
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);
        $notification = new UpcomingEventReminder($event);

        foreach ([$account, $guest] as $recipient) {
            $mail = $notification->toMail($recipient);

            $this->assertSame('https://meet.example.com/updated-room', $notification->actionUrl());
            $this->assertSame('https://meet.example.com/updated-room', $mail->actionUrl);
            $this->assertContains(
                '**Meeting link:** https://meet.example.com/updated-room',
                $mail->introLines
            );
        }
    }

    public function test_reminder_uses_the_current_or_next_program_day_logistics(): void
    {
        Carbon::setTestNow('2026-05-01 10:00:00');

        $event = Event::factory()->create([
            'creator_id' => User::factory()->create()->id,
            'theme' => 'Program Theme',
            'start_date' => now()->addDay(),
            'end_date' => now()->addDays(7),
        ]);
        $event->days()->create([
            'position' => 1,
            'title' => 'Day One',
            'theme' => 'Clarity',
            'start_at' => now()->addDay(),
            'end_at' => now()->addDay()->addHours(2),
            'mode' => 'online',
            'meeting_link' => 'https://meet.example.com/day-one',
        ]);

        $notification = new UpcomingEventReminder($event);
        $payload = $notification->toArray(User::factory()->create());

        $this->assertSame('https://meet.example.com/day-one', $notification->actionUrl());
        $this->assertSame($event->days()->first()->id, $payload['event_day_id']);
        $this->assertSame('Online Event (Access link on event page)', $payload['location']);
    }
}
