<?php

namespace Tests\Feature;

use App\Enums\ApplicationStatus;
use App\Enums\EventStatus;
use App\Enums\Permissions\EventPermissionsEnum;
use App\Enums\SessionFormat;
use App\Enums\SpeakerWorkspaceStage;
use App\Models\Event;
use App\Models\Speaker;
use App\Models\SpeakerApplication;
use App\Models\SpeakerInvite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\URL;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class SpeakerJourneyFeatureTest extends TestCase
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

        Permission::findOrCreate(EventPermissionsEnum::APPLY_TO_SPEAK->value);
        Permission::findOrCreate(EventPermissionsEnum::MANAGE_SPEAKERS->value);
        Permission::findOrCreate('approve-speaker-applications');
    }

    public function test_speaker_application_submission_creates_under_review_workspace(): void
    {
        Notification::fake();

        $user = User::factory()->create();
        $event = $this->makeEvent([
            'is_allowing_application' => true,
        ]);

        $user->givePermissionTo(EventPermissionsEnum::APPLY_TO_SPEAK->value);

        $response = $this->actingAs($user)->post(route('speaker.events.store', $event), [
            'title' => 'Leadership Coach',
            'organization' => 'Beacon Leadership Institute',
            'photo' => UploadedFile::fake()->image('speaker.jpg'),
            'bio' => 'Experienced leadership coach and facilitator.',
            'topic_title' => 'Leading Through Change',
            'topic_description' => 'A practical session on leading teams through uncertainty.',
            'session_format' => SessionFormat::Presentation->value,
            'notes' => 'Happy to adapt the session for emerging leaders.',
        ]);

        $response
            ->assertRedirect(route('speaker.events.show', $event->slug))
            ->assertSessionHas('message', 'Application submitted successfully.');

        $application = SpeakerApplication::query()
            ->where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        $this->assertSame(ApplicationStatus::PENDING->value, $application->status);

        $workspaceResponse = $this->actingAs($user)->get(route('speaker.events.show', $event->slug));

        $workspaceResponse->assertOk();
        $workspaceResponse->assertInertia(fn (Assert $page) => $page
            ->where('stage', SpeakerWorkspaceStage::UNDER_REVIEW->value)
        );
    }

    public function test_accepting_speaker_invitation_opens_approved_workspace(): void
    {
        $user = User::factory()->create();
        $event = $this->makeEvent([
            'is_allowing_application' => true,
        ]);
        $speaker = $this->makeSpeaker($user);

        $invite = SpeakerInvite::query()->create([
            'event_id' => $event->id,
            'speaker_id' => $speaker->id,
            'status' => 'pending',
            'expires_at' => now()->addDay(),
        ]);

        $response = $this->actingAs($user)->post(
            URL::signedRoute('invitations.accept', ['event' => $event, 'invite' => $invite])
        );

        $response
            ->assertRedirect(route('speaker.events.show', $event->slug))
            ->assertSessionHas('message', 'Invitation accepted. Continue from your speaker workspace.');

        $this->assertDatabaseHas('speaker_invites', [
            'id' => $invite->id,
            'status' => 'accepted',
        ]);

        $this->assertDatabaseHas('event_speaker', [
            'event_id' => $event->id,
            'speaker_id' => $speaker->id,
        ]);

        $workspaceResponse = $this->actingAs($user)->get(route('speaker.events.show', $event->slug));

        $workspaceResponse->assertOk();
        $workspaceResponse->assertInertia(fn (Assert $page) => $page
            ->where('stage', SpeakerWorkspaceStage::APPROVED->value)
        );
    }

    public function test_admin_can_approve_speaker_application_and_workspace_becomes_approved(): void
    {
        Notification::fake();

        $admin = User::factory()->create();
        $speakerUser = User::factory()->create();
        $event = $this->makeEvent([
            'is_allowing_application' => true,
        ]);
        $speaker = $this->makeSpeaker($speakerUser);

        $admin->givePermissionTo('approve-speaker-applications');

        $application = SpeakerApplication::query()->create([
            'event_id' => $event->id,
            'user_id' => $speakerUser->id,
            'speaker_id' => $speaker->id,
            'topic_title' => 'Building High-Trust Teams',
            'topic_description' => 'A practical framework for trust-building in teams.',
            'session_format' => SessionFormat::Workshop->value,
            'status' => ApplicationStatus::PENDING->value,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.speakers.application.approve', $application));

        $response->assertRedirect();
        $response->assertSessionHas('message', 'Speaker application approved successfully.');

        $this->assertDatabaseHas('speaker_applications', [
            'id' => $application->id,
            'status' => ApplicationStatus::APPROVED->value,
        ]);

        $this->assertDatabaseHas('event_speaker', [
            'event_id' => $event->id,
            'speaker_id' => $speaker->id,
        ]);

        $workspaceResponse = $this->actingAs($speakerUser)->get(route('speaker.events.show', $event->slug));

        $workspaceResponse->assertOk();
        $workspaceResponse->assertInertia(fn (Assert $page) => $page
            ->where('stage', SpeakerWorkspaceStage::APPROVED->value)
        );
    }

    public function test_admin_can_reject_then_reopen_speaker_application_journey(): void
    {
        $admin = User::factory()->create();
        $speakerUser = User::factory()->create();
        $event = $this->makeEvent([
            'is_allowing_application' => true,
        ]);
        $speaker = $this->makeSpeaker($speakerUser);

        $admin->givePermissionTo('approve-speaker-applications');
        $admin->givePermissionTo(EventPermissionsEnum::MANAGE_SPEAKERS->value);

        $application = SpeakerApplication::query()->create([
            'event_id' => $event->id,
            'user_id' => $speakerUser->id,
            'speaker_id' => $speaker->id,
            'topic_title' => 'Designing Intentional Communities',
            'topic_description' => 'A thoughtful session on community design and leadership.',
            'session_format' => SessionFormat::Panel->value,
            'status' => ApplicationStatus::APPROVED->value,
            'reviewed_at' => now(),
        ]);

        $event->speakers()->syncWithoutDetaching([$speaker->id]);

        $rejectResponse = $this->actingAs($admin)->patch(route('admin.speakers.application.reject', $application), [
            'feedback' => str_repeat('Please sharpen the session scope and audience fit. ', 2),
        ]);

        $rejectResponse->assertRedirect();
        $rejectResponse->assertSessionHas('message', 'Speaker application rejected successfully.');

        $this->assertDatabaseHas('speaker_applications', [
            'id' => $application->id,
            'status' => ApplicationStatus::REJECTED->value,
        ]);

        $rejectedWorkspaceResponse = $this->actingAs($speakerUser)->get(route('speaker.events.show', $event->slug));

        $rejectedWorkspaceResponse->assertOk();
        $rejectedWorkspaceResponse->assertInertia(fn (Assert $page) => $page
            ->where('stage', SpeakerWorkspaceStage::REJECTED->value)
        );

        $reopenResponse = $this->actingAs($admin)->patch(route('admin.speakers.application.revoke', $application));

        $reopenResponse->assertRedirect();
        $reopenResponse->assertSessionHas('message', 'Speaker application approval has been revoked successfully.');

        $this->assertDatabaseHas('speaker_applications', [
            'id' => $application->id,
            'status' => ApplicationStatus::PENDING->value,
        ]);

        $reopenedWorkspaceResponse = $this->actingAs($speakerUser)->get(route('speaker.events.show', $event->slug));

        $reopenedWorkspaceResponse->assertOk();
        $reopenedWorkspaceResponse->assertInertia(fn (Assert $page) => $page
            ->where('stage', SpeakerWorkspaceStage::UNDER_REVIEW->value)
        );
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
            'is_allowing_application' => false,
            'attendee_slots' => 100,
            'entry_fee' => 0,
            'start_date' => now()->addDays(5),
            'end_date' => now()->addDays(6),
        ], $overrides));
    }

    private function makeSpeaker(User $user): Speaker
    {
        return Speaker::query()->create([
            'user_id' => $user->id,
            'created_by' => $user->id,
            'title' => 'Leadership Coach',
            'organization' => 'Beacon Leadership Institute',
            'bio' => 'Speaker bio',
            'photo' => 'speakers_dp/test.jpg',
            'status' => 'active',
        ]);
    }
}
