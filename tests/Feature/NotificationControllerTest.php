<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_index_normalizes_legacy_decimal_event_reminder_time(): void
    {
        $user = User::factory()->create([
            'email_verified_at' => now(),
        ]);

        DB::table('notifications')->insert([
            'id' => (string) Str::uuid(),
            'type' => 'App\\Notifications\\UpcomingEventReminder',
            'notifiable_type' => User::class,
            'notifiable_id' => $user->id,
            'data' => json_encode([
                'type' => 'event_reminder',
                'message' => 'Reminder: BCCI Discipleship Track is starting in 1.0034034421181 days!',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($user)->getJson(route('notifications.index'));

        $response
            ->assertOk()
            ->assertJsonPath('notifications.0.message', 'Reminder: BCCI Discipleship Track is starting tomorrow!')
            ->assertJsonPath('notifications.0.data.message', 'Reminder: BCCI Discipleship Track is starting tomorrow!');
    }
}
