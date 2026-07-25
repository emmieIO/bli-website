<?php

namespace Tests\Feature;

use App\Enums\SpeakerStatus;
use App\Enums\UserRoles;
use App\Models\Speaker;
use App\Models\User;
use App\Notifications\SpeakerAccountCreatedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PublicSpeakerRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_speaker_registration_creates_a_pending_profile_without_speaker_access(): void
    {
        Notification::fake();
        Storage::fake('public');
        Role::findOrCreate(UserRoles::SPEAKER->value, 'web');

        $response = $this->post(route('become-a-speaker.store'), [
            'name' => 'Ada Speaker',
            'email' => 'ada@example.com',
            'phone' => '+2348031234567',
            'headline' => 'Leadership Coach',
            'organization' => 'Beacon Institute',
            'password' => 'secure-password',
            'password_confirmation' => 'secure-password',
            'bio' => 'I help emerging leaders build healthy and effective teams.',
            'photo' => UploadedFile::fake()->image('ada.jpg'),
            'linkedin' => 'https://linkedin.com/in/adaspeaker',
            'website' => 'https://ada.example.com',
            'agree_terms' => true,
        ]);

        $response
            ->assertRedirect(route('login'))
            ->assertSessionHas('message', 'Your speaker application has been submitted for review.');

        $user = User::query()->where('email', 'ada@example.com')->firstOrFail();
        $speaker = Speaker::query()->where('user_id', $user->id)->firstOrFail();

        $this->assertSame('Leadership Coach', $speaker->title);
        $this->assertSame('Beacon Institute', $speaker->organization);
        $this->assertSame(SpeakerStatus::PENDING, $speaker->status);
        $this->assertFalse($user->hasRole(UserRoles::SPEAKER->value));
        Storage::disk('public')->assertExists($speaker->photo);

        Notification::assertSentTo(
            $user,
            SpeakerAccountCreatedNotification::class,
            fn (SpeakerAccountCreatedNotification $notification) => ! $notification->isAdminCreated
        );
    }

    public function test_public_speaker_registration_returns_clear_validation_errors(): void
    {
        $response = $this->from(route('become-a-speaker'))
            ->post(route('become-a-speaker.store'), []);

        $response
            ->assertRedirect(route('become-a-speaker'))
            ->assertSessionHasErrors([
                'name',
                'email',
                'phone',
                'headline',
                'password',
                'bio',
                'photo',
                'agree_terms',
            ]);
    }
}
