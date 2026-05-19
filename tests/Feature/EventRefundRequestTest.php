<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\EventStatus;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Models\Event;
use App\Models\EventRefundRequest;
use App\Models\Transaction;
use App\Models\User;
use App\Notifications\EventRefundRequestedNotification;
use App\Notifications\EventRefundRequestReviewedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class EventRefundRequestTest extends TestCase
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

        Permission::findOrCreate(EventPermissionsEnum::VIEW_ANY->value);
        Permission::findOrCreate(EventPermissionsEnum::MANAGE_ATTENDEES->value);
    }

    public function test_paid_attendee_can_request_refund_from_event_workspace(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $creator = User::factory()->create();
        $admin = User::factory()->create();
        $admin->givePermissionTo(EventPermissionsEnum::VIEW_ANY->value);

        $event = $this->makeEvent($creator, [
            'entry_fee' => 5000,
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'payable_id' => $event->id,
            'payable_type' => Event::class,
            'transaction_id' => 'BLI_EVENT_REFUND_REQUEST_' . time(),
            'payment_ref' => 'REFUND_REQUEST_' . time(),
            'amount' => 5000,
            'currency' => 'NGN',
            'status' => 'successful',
            'paid_at' => now(),
            'metadata' => ['type' => 'event', 'event_id' => $event->id],
        ]);

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('user.request-event-refund', $event->slug), [
            'reason' => 'Schedule conflict',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Your refund request has been submitted and is pending admin review.');

        $refundRequest = EventRefundRequest::query()->firstOrFail();

        $this->assertSame('pending', $refundRequest->status);
        $this->assertSame($transaction->id, $refundRequest->transaction_id);

        Notification::assertSentTo($creator, EventRefundRequestedNotification::class);
        Notification::assertSentTo($admin, EventRefundRequestedNotification::class);
    }

    public function test_admin_can_approve_refund_request_and_registration_becomes_refunded(): void
    {
        Notification::fake();

        $admin = User::factory()->create();
        $admin->givePermissionTo(EventPermissionsEnum::MANAGE_ATTENDEES->value);

        $user = User::factory()->create();
        $waitlistedUser = User::factory()->create();
        $creator = User::factory()->create();
        $event = $this->makeEvent($creator, [
            'entry_fee' => 5000,
            'attendee_slots' => 1,
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'payable_id' => $event->id,
            'payable_type' => Event::class,
            'transaction_id' => 'BLI_EVENT_REFUND_APPROVE_' . time(),
            'payment_ref' => 'REFUND_APPROVE_' . time(),
            'amount' => 5000,
            'currency' => 'NGN',
            'status' => 'successful',
            'paid_at' => now(),
            'metadata' => ['type' => 'event', 'event_id' => $event->id],
        ]);

        $event->attendees()->attach($user->id, [
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

        $refundRequest = EventRefundRequest::query()->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'transaction_id' => $transaction->id,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        $response = $this->actingAs($admin)->post(route('admin.events.refund-requests.approve', $refundRequest->id));

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Refund request approved and registration updated.');

        $this->assertDatabaseHas('event_refund_requests', [
            'id' => $refundRequest->id,
            'status' => 'approved',
            'reviewed_by' => $admin->id,
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'refunded',
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => EventRegistrationStatus::REFUNDED->value,
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $waitlistedUser->id,
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);

        Notification::assertSentTo($user, EventRefundRequestReviewedNotification::class);
    }

    public function test_admin_can_decline_refund_request_without_changing_registration(): void
    {
        Notification::fake();

        $admin = User::factory()->create();
        $admin->givePermissionTo(EventPermissionsEnum::MANAGE_ATTENDEES->value);

        $user = User::factory()->create();
        $creator = User::factory()->create();
        $event = $this->makeEvent($creator, [
            'entry_fee' => 5000,
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'payable_id' => $event->id,
            'payable_type' => Event::class,
            'transaction_id' => 'BLI_EVENT_REFUND_DECLINE_' . time(),
            'payment_ref' => 'REFUND_DECLINE_' . time(),
            'amount' => 5000,
            'currency' => 'NGN',
            'status' => 'successful',
            'paid_at' => now(),
            'metadata' => ['type' => 'event', 'event_id' => $event->id],
        ]);

        $event->attendees()->attach($user->id, [
            'status' => EventRegistrationStatus::REGISTERED->value,
            'revoke_count' => 0,
        ]);

        $refundRequest = EventRefundRequest::query()->create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'transaction_id' => $transaction->id,
            'status' => 'pending',
            'requested_at' => now(),
        ]);

        $response = $this->actingAs($admin)->patch(route('admin.events.refund-requests.decline', $refundRequest->id), [
            'admin_note' => 'Refund window has closed.',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Refund request declined.');

        $this->assertDatabaseHas('event_refund_requests', [
            'id' => $refundRequest->id,
            'status' => 'declined',
            'reviewed_by' => $admin->id,
            'admin_note' => 'Refund window has closed.',
        ]);

        $this->assertDatabaseHas('event_attendees', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'status' => EventRegistrationStatus::REGISTERED->value,
        ]);

        $this->assertDatabaseHas('transactions', [
            'id' => $transaction->id,
            'status' => 'successful',
        ]);

        Notification::assertSentTo($user, EventRefundRequestReviewedNotification::class);
    }

    private function makeEvent(User $creator, array $overrides = []): Event
    {
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
