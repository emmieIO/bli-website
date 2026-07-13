<?php

namespace Tests\Feature;

use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicEventIndexSegmentationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_events_index_groups_events_by_lifecycle_and_capacity(): void
    {
        $creator = User::factory()->create();

        $live = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Live Event',
            'status' => EventStatus::LIVE->value,
            'start_date' => now()->subHour(),
            'end_date' => now()->addHour(),
        ]);

        $open = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Open Event',
            'status' => EventStatus::REGISTRATION_OPEN->value,
            'attendee_slots' => 10,
        ]);

        $full = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Full Event',
            'status' => EventStatus::REGISTRATION_OPEN->value,
            'attendee_slots' => 0,
        ]);

        $announced = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Announced Event',
            'status' => EventStatus::PUBLISHED->value,
        ]);

        $closed = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Closed Event',
            'status' => EventStatus::REGISTRATION_CLOSED->value,
        ]);

        $completed = Event::factory()->create([
            'creator_id' => $creator->id,
            'theme' => 'Completed Event',
            'status' => EventStatus::COMPLETED->value,
            'start_date' => now()->subDays(3),
            'end_date' => now()->subDays(2),
        ]);

        $response = $this->get(route('events.index'));

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Events/Index')
            ->where('segmentCounts.live_now', 1)
            ->where('segmentCounts.open_registration', 1)
            ->where('segmentCounts.event_full', 1)
            ->where('segmentCounts.announced', 1)
            ->where('segmentCounts.registration_closed', 1)
            ->where('segmentCounts.completed', 1)
            ->where('sections.0.key', 'live_now')
            ->where('sections.1.key', 'open_registration')
            ->where('sections.2.key', 'event_full')
            ->where('sections.3.key', 'announced')
            ->where('sections.4.key', 'registration_closed')
            ->where('sections.5.key', 'completed'));
    }
}
