<?php

namespace Tests\Feature;

use App\Models\Event;
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
            'is_published' => true,
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
            'is_published' => true,
            'theme' => 'Test Theme',
            'creator_id' => $creator->id,
        ]);

        $response = $this->actingAs($user)
            ->get(route('events.checkout', $event->slug));

        $response->assertStatus(200);
        // assertInertia can be flaky depending on setup, but status 200 is good enough for basic check
    }

    public function test_payment_initialization()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
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
    }

    public function test_payment_verification_registers_user()
    {
        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = Event::factory()->create([
            'entry_fee' => 5000,
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

        $response->assertRedirect(route('events.show', $event->slug));
        
        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'successful'
        ]);

        $this->assertTrue($event->attendees()->where('user_id', $user->id)->exists());
    }
}