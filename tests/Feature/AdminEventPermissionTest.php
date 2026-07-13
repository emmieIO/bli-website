<?php

namespace Tests\Feature;

use App\Enums\EventRegistrationStatus;
use App\Enums\EventStatus;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class AdminEventPermissionTest extends TestCase
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
        $this->registerEventPermissions();
    }

    public function test_user_without_manage_speakers_permission_cannot_access_assign_speakers_page(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent();

        $this->grantPermissions($user, [
            EventPermissionsEnum::VIEW_ANY->value,
        ]);

        $response = $this->actingAs($user)->get(
            route('admin.events.assign-speakers', $event->slug)
        );

        $response->assertForbidden();
    }

    public function test_user_with_manage_speakers_permission_can_assign_speakers_to_event(): void
    {
        $manager = User::factory()->create();
        $speakerUser = User::factory()->create();
        $event = $this->makeEvent();
        $speaker = Speaker::query()->create([
            'user_id' => $speakerUser->id,
            'created_by' => $manager->id,
            'title' => 'Leadership Coach',
            'organization' => 'Beacon Leadership Institute',
            'bio' => 'Experienced speaker',
            'status' => 'active',
        ]);

        $this->grantPermissions($manager, [
            EventPermissionsEnum::MANAGE_SPEAKERS->value,
        ]);

        $response = $this->actingAs($manager)->post(
            route('admin.events.assign-speaker', $event->slug),
            ['speaker_ids' => [$speaker->id]]
        );

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Speakers assigned successfully.');

        $this->assertDatabaseHas('event_speaker', [
            'event_id' => $event->id,
            'speaker_id' => $speaker->id,
        ]);
    }

    public function test_event_workspace_hides_payment_data_without_view_payments_permission(): void
    {
        $manager = User::factory()->create();
        $payer = User::factory()->create();
        $event = $this->makeEvent([
            'entry_fee' => 3500,
        ]);

        $this->grantPermissions($manager, [
            EventPermissionsEnum::VIEW_ANY->value,
        ]);

        Transaction::query()->create([
            'user_id' => $payer->id,
            'payable_type' => Event::class,
            'payable_id' => $event->id,
            'transaction_id' => 'TXN_NO_VIEW_PAYMENTS',
            'amount' => 3500,
            'currency' => 'NGN',
            'status' => 'successful',
        ]);

        $response = $this->actingAs($manager)->get(route('admin.events.show', $event->slug));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Events/View')
            ->where('capabilities.canViewPayments', false)
            ->has('event.transactions', 0));
    }

    public function test_event_workspace_includes_payment_data_with_view_payments_permission(): void
    {
        $manager = User::factory()->create();
        $payer = User::factory()->create();
        $event = $this->makeEvent([
            'entry_fee' => 3500,
        ]);

        $this->grantPermissions($manager, [
            EventPermissionsEnum::VIEW_ANY->value,
            EventPermissionsEnum::VIEW_PAYMENTS->value,
        ]);

        $transaction = Transaction::query()->create([
            'user_id' => $payer->id,
            'payable_type' => Event::class,
            'payable_id' => $event->id,
            'transaction_id' => 'TXN_WITH_VIEW_PAYMENTS',
            'amount' => 3500,
            'currency' => 'NGN',
            'status' => 'successful',
        ]);

        $response = $this->actingAs($manager)->get(route('admin.events.show', $event->slug));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Admin/Events/View')
            ->where('capabilities.canViewPayments', true)
            ->has('event.transactions', 1)
            ->where('event.transactions.0.id', $transaction->id));
    }

    private function grantPermissions(User $user, array $permissions): void
    {
        $user->givePermissionTo($permissions);
    }

    private function registerEventPermissions(): void
    {
        foreach (EventPermissionsEnum::cases() as $permission) {
            Permission::findOrCreate($permission->value);
        }
    }

    private function makeEvent(array $overrides = []): Event
    {
        $creator = User::factory()->create();

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
