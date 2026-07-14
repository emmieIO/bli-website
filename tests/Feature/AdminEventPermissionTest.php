<?php

namespace Tests\Feature;

use App\Enums\EventStatus;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Models\Event;
use App\Models\EventGuestAttendee;
use App\Models\Speaker;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Http\Middleware\ValidateCsrfToken;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
            VerifyCsrfToken::class,
            ValidateCsrfToken::class,
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

    public function test_create_event_returns_field_errors_for_invalid_input(): void
    {
        $user = User::factory()->create();
        $this->grantPermissions($user, [EventPermissionsEnum::CREATE->value]);

        $response = $this->actingAs($user)->post(route('admin.events.store'), [
            'description' => '<p><br></p>',
            'creator_id' => $user->id,
        ]);

        $response->assertSessionHasErrors([
            'title',
            'theme',
            'description',
            'mode',
            'start_date',
            'end_date',
        ]);
    }

    public function test_create_event_normalizes_and_persists_a_valid_payload(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();
        $this->grantPermissions($user, [EventPermissionsEnum::CREATE->value]);

        $response = $this->actingAs($user)->post(route('admin.events.store'), [
            'title' => '  Formation Intensive  ',
            'theme' => '  Character and Capacity  ',
            'description' => '<p>Focused leadership formation.</p>',
            'mode' => 'online',
            'location' => 'https://meet.example.com/formation',
            'physical_address' => 'This stale value must not be stored',
            'attendee_slots' => '40',
            'start_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'end_date' => now()->addDays(2)->addHours(2)->format('Y-m-d H:i:s'),
            'creator_id' => $otherUser->id,
            'status' => EventStatus::DRAFT->value,
            'entry_fee' => '0',
            'require_sign_up' => '0',
            'metadata' => [
                'program_type' => 'discipleship_track',
                'program_code' => 'BDT',
                'registration_mode' => 'selective',
                'requires_screening' => '1',
                'cohort_duration_weeks' => '12',
                'weekly_prayer_target_minutes' => '420',
                'weekly_evangelism_target_min' => '3',
                'weekly_evangelism_target_max' => '5',
                'weekly_discipleship_target_min' => '1',
                'weekly_discipleship_target_max' => '3',
            ],
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.events.index'));

        $event = Event::query()->where('title', 'Formation Intensive')->firstOrFail();

        $this->assertSame($user->id, $event->creator_id);
        $this->assertNull($event->physical_address);
        $this->assertFalse($event->require_sign_up);
        $this->assertTrue($event->metadata['requires_screening']);
        $this->assertSame(12, $event->metadata['cohort_duration_weeks']);
        $this->assertSame(420, $event->metadata['weekly_prayer_target_minutes']);
        $this->assertSame(3, $event->metadata['weekly_evangelism_target_min']);
    }

    public function test_create_event_stores_its_cover_on_the_public_disk(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $this->grantPermissions($user, [EventPermissionsEnum::CREATE->value]);

        $response = $this->actingAs($user)->post(route('admin.events.store'), [
            'title' => 'Event With Cover',
            'theme' => 'Visible Leadership',
            'description' => '<p>An event with a permanent cover.</p>',
            'mode' => 'online',
            'location' => 'https://meet.example.com/cover',
            'start_date' => now()->addDays(2)->format('Y-m-d H:i:s'),
            'end_date' => now()->addDays(2)->addHours(2)->format('Y-m-d H:i:s'),
            'creator_id' => $user->id,
            'entry_fee' => '0',
            'program_cover' => UploadedFile::fake()->image('event-cover.jpg', 1200, 600),
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.events.index'));

        $event = Event::query()->where('title', 'Event With Cover')->firstOrFail();

        $this->assertStringStartsWith('program_covers/', $event->program_cover);
        $this->assertStringNotContainsString('/tmp/', $event->program_cover);
        Storage::disk('public')->assertExists($event->program_cover);
    }

    public function test_update_event_replaces_its_cover_with_a_permanent_file(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();
        $event = $this->makeEvent([
            'creator_id' => $user->id,
            'mode' => 'online',
            'location' => 'https://meet.example.com/replacement-cover',
            'physical_address' => null,
        ]);
        $this->grantPermissions($user, [EventPermissionsEnum::UPDATE_ANY->value]);

        $response = $this->actingAs($user)->post(route('admin.events.update', $event->slug), [
            '_method' => 'put',
            'title' => $event->title,
            'theme' => $event->theme,
            'description' => $event->description,
            'mode' => $event->mode,
            'location' => $event->location,
            'physical_address' => $event->physical_address,
            'attendee_slots' => $event->attendee_slots,
            'start_date' => $event->start_date->format('Y-m-d H:i:s'),
            'end_date' => $event->end_date->format('Y-m-d H:i:s'),
            'creator_id' => $event->creator_id,
            'status' => $event->status->value,
            'entry_fee' => $event->entry_fee,
            'program_cover' => UploadedFile::fake()->image('replacement-cover.png', 1200, 600),
        ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect(route('admin.events.index'));

        $event->refresh();

        $this->assertStringStartsWith('program_covers/', $event->program_cover);
        $this->assertStringNotContainsString('/tmp/', $event->program_cover);
        Storage::disk('public')->assertExists($event->program_cover);
    }

    public function test_paid_event_payload_requires_account_sign_up(): void
    {
        $user = User::factory()->create();
        $this->grantPermissions($user, [EventPermissionsEnum::CREATE->value]);

        $response = $this->actingAs($user)->post(route('admin.events.store'), [
            'title' => 'Paid Leadership Forum',
            'theme' => 'Executive Leadership',
            'description' => '<p>A paid leadership forum.</p>',
            'mode' => 'online',
            'location' => 'https://meet.example.com/forum',
            'start_date' => now()->addDays(3)->format('Y-m-d H:i:s'),
            'end_date' => now()->addDays(3)->addHours(2)->format('Y-m-d H:i:s'),
            'creator_id' => $user->id,
            'entry_fee' => '5000',
            'require_sign_up' => '0',
        ]);

        $response->assertSessionHasErrors([
            'entry_fee' => 'Paid events must require account sign-up so payment can be linked to an attendee.',
        ]);
        $this->assertDatabaseMissing('events', ['title' => 'Paid Leadership Forum']);
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

    public function test_event_workspace_and_index_include_email_guest_registrations(): void
    {
        $manager = User::factory()->create();
        $accountAttendee = User::factory()->create();
        $event = $this->makeEvent(['creator_id' => $manager->id]);

        $event->attendees()->attach($accountAttendee->id, [
            'status' => 'registered',
            'revoke_count' => 0,
        ]);
        EventGuestAttendee::query()->create([
            'event_id' => $event->id,
            'name' => 'Guest Participant',
            'email' => 'guest.participant@example.com',
            'status' => 'registered',
        ]);

        $this->grantPermissions($manager, [EventPermissionsEnum::VIEW_ANY->value]);

        $this->actingAs($manager)
            ->get(route('admin.events.show', $event->slug))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Admin/Events/View')
                ->has('event.attendees', 1)
                ->has('event.guest_attendees', 1)
                ->where('event.guest_attendees.0.name', 'Guest Participant')
                ->where('event.guest_attendees.0.email', 'guest.participant@example.com')
                ->where('event.guest_attendees.0.status', 'registered'));

        $this->actingAs($manager)
            ->get(route('admin.events.index'))
            ->assertOk()
            ->assertInertia(fn ($page) => $page
                ->component('Admin/Events/Index')
                ->where('events.data.0.attendees_count', 1)
                ->where('events.data.0.guest_attendees_count', 1));
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
