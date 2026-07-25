<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Models\Event;
use App\Models\EventGuestAttendee;
use App\Models\User;
use App\Notifications\EventGuestAccessCodeNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PublicGuestAccessCodeResendTest extends TestCase
{
    use RefreshDatabase;

    public function test_confirmed_guest_can_request_a_new_code_with_their_email(): void
    {
        Notification::fake();

        $event = $this->makeEvent();
        $guest = $this->createGuest($event);
        $newCode = null;

        $this->post(route('events.guest-access.resend', $event->slug), [
            'guest_access_email' => 'GUEST@example.com',
        ])->assertSessionHas(
            'message',
            'If this email is on the attendee list, a new access code has been queued.'
        );

        Notification::assertSentTo(
            $guest,
            EventGuestAccessCodeNotification::class,
            function (EventGuestAccessCodeNotification $notification) use (&$newCode): bool {
                $newCode = $notification->accessCode;

                return preg_match('/^\d{6}$/', $newCode) === 1;
            }
        );

        $guest->refresh();
        $this->assertTrue(Hash::check($newCode, $guest->access_code_hash));
        $this->assertTrue($guest->access_code_expires_at->isFuture());
    }

    public function test_unknown_or_cancelled_guest_receives_the_same_response_without_an_email(): void
    {
        Notification::fake();

        $event = $this->makeEvent();
        $this->createGuest($event, EventRegistrationStatus::CANCELLED);

        foreach (['guest@example.com', 'unknown@example.com'] as $email) {
            $this->post(route('events.guest-access.resend', $event->slug), [
                'guest_access_email' => $email,
            ])->assertSessionHas(
                'message',
                'If this email is on the attendee list, a new access code has been queued.'
            );
        }

        Notification::assertNothingSent();
    }

    private function makeEvent(): Event
    {
        return Event::factory()->create([
            'creator_id' => User::factory(),
            'theme' => 'Leadership and service',
            'status' => 'live',
            'mode' => 'online',
            'location' => 'https://meet.example.com/event-room',
            'require_sign_up' => false,
        ]);
    }

    private function createGuest(
        Event $event,
        EventRegistrationStatus $status = EventRegistrationStatus::REGISTERED
    ): EventGuestAttendee {
        return EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'name' => 'Guest Attendee',
            'email' => 'guest@example.com',
            'status' => $status->value,
        ]);
    }
}
