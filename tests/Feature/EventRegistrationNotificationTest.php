<?php

namespace Tests\Feature;

use App\Events\EventRegisterEvent;
use App\Listeners\EventRegisterListener;
use App\Models\Event;
use App\Models\User;
use App\Notifications\EventRegisteredNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class EventRegistrationNotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_standard_registration_sends_standard_confirmation_notification(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $event = $this->makeEvent();

        app(EventRegisterListener::class)->handle(
            new EventRegisterEvent($event, $user, 'confirmed')
        );

        Notification::assertSentTo($user, EventRegisteredNotification::class, function (EventRegisteredNotification $notification) use ($user) {
            $payload = $notification->toArray($user);

            return $payload['type'] === 'event_registration'
                && str_contains($payload['message'], 'has been confirmed');
        });
    }

    private function makeEvent(): Event
    {
        $creator = User::factory()->create();

        return Event::factory()->create([
            'theme' => 'Beacon Summit',
            'creator_id' => $creator->id,
            'status' => 'registration_open',
            'is_published' => true,
            'is_active' => true,
            'attendee_slots' => 100,
            'entry_fee' => 0,
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(6),
        ]);
    }
}
