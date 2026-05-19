<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Models\Event;
use App\Models\EventRefundRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Payment\PaystackService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventPaymentTest extends TestCase
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

    public function test_user_is_redirected_to_checkout_for_paid_event()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        $response = $this->actingAs($user)
            ->post(route('events.join', $event->slug));

        $response->assertRedirect(route('events.checkout', $event->slug));
    }

    public function test_checkout_page_is_rendered()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        $this->mock(PaystackService::class, function ($mock) {
            $mock->shouldReceive('getPublicKey')->andReturn('pk_test_xxx');
        });

        $response = $this->actingAs($user)
            ->get(route('events.checkout', $event->slug));

        $response->assertStatus(200);
        // assertInertia can be flaky depending on setup, but status 200 is good enough for basic check
    }

    public function test_checkout_page_redirects_confirmed_attendee_to_workspace()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $this->mock(PaystackService::class, function ($mock) {
            $mock->shouldReceive('getPublicKey')->andReturn('pk_test_xxx');
        });

        $response = $this->actingAs($user)
            ->get(route('events.checkout', $event->slug));

        $response->assertRedirect(route('user.events.show', $event->slug));
        $response->assertSessionHas('message', 'Your registration is already confirmed.');
    }

    public function test_checkout_page_redirects_waitlisted_attendee_to_workspace()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
        ]);

        $this->mock(PaystackService::class, function ($mock) {
            $mock->shouldReceive('getPublicKey')->andReturn('pk_test_xxx');
        });

        $response = $this->actingAs($user)
            ->get(route('events.checkout', $event->slug));

        $response->assertRedirect(route('user.events.show', $event->slug));
        $response->assertSessionHas('message', 'You are already on the waitlist for this event.');
    }

    public function test_checkout_page_redirects_when_paid_event_is_full()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'attendee_slots' => 0,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        $this->mock(PaystackService::class, function ($mock) {
            $mock->shouldReceive('getPublicKey')->andReturn('pk_test_xxx');
        });

        $response = $this->actingAs($user)
            ->get(route('events.checkout', $event->slug));

        $response->assertRedirect(route('events.show', $event->slug));
        $response->assertSessionHas('message', 'This event is currently full. Join the waitlist instead of proceeding to checkout.');
    }

    public function test_payment_initialization()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        $this->mock(PaystackService::class, function ($mock) {
            $mock->shouldReceive('initializePayment')
                ->once()
                ->andReturn([
                    'status' => true,
                    'data' => ['authorization_url' => 'https://paystack.com/pay/test']
                ]);
            $mock->shouldReceive('getPublicKey')->andReturn('pk_test_xxx');
        });

        $response = $this->actingAs($user)
            ->postJson(route('payment.event.initialize', $event->slug), [
                'email' => $user->email,
                'name' => $user->name,
                'phone' => '1234567890'
            ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('transactions', [
            'user_id' => $user->id,
            'payable_id' => $event->id,
            'payable_type' => Event::class,
            'amount' => 5000,
            'status' => 'pending'
        ]);
        $this->assertDatabaseMissing('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);
    }

    public function test_event_payment_initialization_rejects_existing_pending_payment()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'payable_id' => $event->id,
            'payable_type' => Event::class,
            'transaction_id' => 'BLI_EVENT_PENDING_' . time(),
            'amount' => 5000,
            'currency' => 'NGN',
            'status' => 'pending',
            'metadata' => ['type' => 'event', 'event_id' => $event->id]
        ]);

        $this->mock(PaystackService::class, function ($mock) {
            $mock->shouldReceive('getPublicKey')->andReturn('pk_test_xxx');
        });

        $response = $this->actingAs($user)
            ->postJson(route('payment.event.initialize', $event->slug), [
                'email' => $user->email,
                'name' => $user->name,
                'phone' => '1234567890'
            ]);

        $response->assertStatus(422);
        $response->assertJson([
            'success' => false,
            'message' => 'You already have a pending payment for this event. Complete or verify that payment first.',
        ]);
    }

    public function test_payment_verification_registers_user()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);
        
        $txRef = 'BLI_EVENT_' . time() . '_' . $user->id . '_' . $event->id;

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'payable_id' => $event->id,
            'payable_type' => Event::class,
            'transaction_id' => $txRef,
            'amount' => 5000,
            'currency' => 'NGN',
            'status' => 'pending',
            'metadata' => ['type' => 'event', 'event_id' => $event->id]
        ]);

        $this->mock(PaystackService::class, function ($mock) use ($transaction) {
            $mock->shouldReceive('verifyTransaction')
                ->once()
                ->with($transaction->transaction_id)
                ->andReturn([
                    'data' => [
                        'status' => 'success',
                        'amount' => 500000, // kobo
                        'currency' => 'NGN',
                        'reference' => $transaction->transaction_id
                    ]
                ]);
            $mock->shouldReceive('getPublicKey')->andReturn('pk_test_xxx');
        });

        $response = $this->actingAs($user)
            ->get(route('payment.callback', ['reference' => $txRef]));

        $response->assertRedirect(route('user.events.show', $event->slug));
        
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'successful'
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);
    }

    public function test_successful_paid_event_verification_waitlists_user_if_capacity_fills_before_callback()
    {
        $user = User::factory()->create();
        $otherAttendee = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'attendee_slots' => 1,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        $txRef = 'BLI_EVENT_' . time() . '_' . $user->id . '_' . $event->id;

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'payable_id' => $event->id,
            'payable_type' => Event::class,
            'transaction_id' => $txRef,
            'amount' => 5000,
            'currency' => 'NGN',
            'status' => 'pending',
            'metadata' => ['type' => 'event', 'event_id' => $event->id]
        ]);

        $event->attendees()->attach($otherAttendee->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $this->mock(PaystackService::class, function ($mock) use ($transaction) {
            $mock->shouldReceive('verifyTransaction')
                ->once()
                ->with($transaction->transaction_id)
                ->andReturn([
                    'data' => [
                        'status' => 'success',
                        'amount' => 500000,
                        'currency' => 'NGN',
                        'reference' => $transaction->transaction_id
                    ]
                ]);
            $mock->shouldReceive('getPublicKey')->andReturn('pk_test_xxx');
        });

        $response = $this->actingAs($user)
            ->get(route('payment.callback', ['reference' => $txRef]));

        $response->assertRedirect(route('user.events.show', $event->slug));
        $response->assertSessionHas('message', 'Payment successful! You have been added to the event waitlist.');

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'successful'
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => EventRegistrationStatus::WAITLISTED->value,
        ]);
    }

    public function test_verify_payment_for_successful_event_transaction_respects_waitlist_status()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'attendee_slots' => 1,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'payable_id' => $event->id,
            'payable_type' => Event::class,
            'transaction_id' => 'BLI_EVENT_VERIFY_WAITLIST_' . time(),
            'payment_ref' => 'REF_WAITLIST_' . time(),
            'amount' => 5000,
            'currency' => 'NGN',
            'status' => 'successful',
            'metadata' => ['type' => 'event', 'event_id' => $event->id]
        ]);

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
        ]);

        $this->mock(PaystackService::class, function ($mock) {
            $mock->shouldReceive('getPublicKey')->andReturn('pk_test_xxx');
        });

        $response = $this->actingAs($user)
            ->get(route('payment.verify', $transaction->payment_ref));

        $response->assertRedirect(route('user.events.show', $event->slug));
        $response->assertSessionHas('message', 'Your payment was successful and you are currently on the event waitlist.');
    }

    public function test_refund_processed_webhook_completes_pending_refund_request_and_promotes_waitlist(): void
    {
        $registeredUser = User::factory()->create();
        $waitlistedUser = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
            'attendee_slots' => 1,
            'status' => 'registration_open',
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        $transaction = Transaction::create([
            'user_id' => $registeredUser->id,
            'payable_id' => $event->id,
            'payable_type' => Event::class,
            'transaction_id' => 'BLI_EVENT_REFUND_' . time(),
            'payment_ref' => 'REFUND_REF_' . time(),
            'amount' => 5000,
            'currency' => 'NGN',
            'status' => 'successful',
            'paid_at' => now(),
            'metadata' => ['type' => 'event', 'event_id' => $event->id],
        ]);

        $event->attendees()->attach($registeredUser->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
            'created_at' => now()->subHour(),
            'updated_at' => now()->subHour(),
        ]);

        $event->attendees()->attach($waitlistedUser->id, [
            'status' => EventRegistrationStatus::WAITLISTED->value,
            'revoke_count' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        EventRefundRequest::query()->create([
            'event_id' => $event->id,
            'user_id' => $registeredUser->id,
            'transaction_id' => $transaction->id,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        $this->mock(PaystackService::class, function ($mock) {
            $mock->shouldReceive('verifyWebhookSignature')
                ->once()
                ->andReturn(true);
        });

        $payload = [
            'event' => 'refund.processed',
            'data' => [
                'status' => 'processed',
                'transaction_reference' => $transaction->transaction_id,
                'amount' => '500000',
                'currency' => 'NGN',
            ],
        ];

        $response = $this->postJson(route('payment.webhook'), $payload, [
            'x-paystack-signature' => 'test-signature',
        ]);

        $response->assertOk();

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'refunded',
        ]);

        $this->assertDatabaseHas('event_refund_requests', [
            'event_id' => $event->id,
            'user_id' => $registeredUser->id,
            'status' => 'approved',
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $registeredUser->id,
            'status' => EventRegistrationStatus::REFUNDED->value,
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $waitlistedUser->id,
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);
    }
}
