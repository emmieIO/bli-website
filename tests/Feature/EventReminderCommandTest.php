<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\EventStatus;
use App\Models\Event;
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
        $waitlistedUser = User::factory()->create();
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

        $event->attendees()->attach($waitlistedUser->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
        ]);

        $event->attendees()->attach($cancelledUser->id, [
            'status' => EventRegistrationStatus::CANCELLED->value,
            'revoke_count' => 1,
        ]);

        $this->artisan('app:send-event-reminders')->assertExitCode(0);

        Notification::assertSentToTimes($confirmedUser, UpcomingEventReminder::class, 1);
        Notification::assertNotSentTo($waitlistedUser, UpcomingEventReminder::class);
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
}
