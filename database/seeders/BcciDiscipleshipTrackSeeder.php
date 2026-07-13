<?php

namespace Database\Seeders;

use App\Enums\EventModeEnum;
use App\Enums\EventRegistrationStatus;
use App\Enums\EventStatus;
use App\Models\Event;
use App\Models\EventResource;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BcciDiscipleshipTrackSeeder extends Seeder
{
    public function run(): void
    {
        $organizer = User::firstOrCreate(
            ['email' => 'markonuoha97@gmail.com'],
            [
                'name' => 'Nnaemeka Mark Onuoha',
                'phone' => '123-456-7890',
                'password' => 'password',
            ]
        );

        if (method_exists($organizer, 'assignRole')) {
            $organizer->assignRole('admin');
        }

        $event = Event::updateOrCreate(
            ['slug' => 'bcci-discipleship-track-ideal'],
            [
                'uuid' => (string) Str::uuid(),
                'title' => 'BCCI Discipleship Track',
                'theme' => 'The Multiplication Mandate',
                'description' => $this->description(),
                'mode' => EventModeEnum::HYBRID->value,
                'location' => 'https://meet.google.com/bcci-ideal-track',
                'physical_address' => 'Beacon Christian Centre International, Leadership Hall, Umuahia',
                'attendee_slots' => 40,
                'contact_email' => 'discipleship@bcci.example',
                'start_date' => now()->addDays(10)->setTime(18, 0),
                'end_date' => now()->addWeeks(6)->setTime(20, 0),
                'creator_id' => $organizer->id,
                'status' => EventStatus::REGISTRATION_OPEN->value,
                'is_active' => true,
                'is_published' => true,
                'is_allowing_application' => false,
                'is_featured' => true,
                'entry_fee' => 3500,
                'metadata' => [
                    'program_type' => 'discipleship_track',
                    'program_code' => 'BDT',
                    'registration_mode' => 'selective',
                    'requires_screening' => true,
                    'screening_note' => 'Payment of the registration fee does not guarantee acceptance. Every applicant will be screened for spiritual consistency, teachability, discipline, and hunger for growth before final admission.',
                    'cohort_duration_weeks' => 6,
                    'group_model' => 'Cluster-based accountability groups led by core disciples (D1).',
                    'central_teaching_schedule' => 'Once weekly central teaching, 60-90 minutes.',
                    'group_meeting_schedule' => 'Once weekly cluster meeting for reflection, accountability, and application.',
                    'weekly_prayer_target_minutes' => 420,
                    'weekly_evangelism_target_min' => 3,
                    'weekly_evangelism_target_max' => 5,
                    'weekly_discipleship_target_min' => 1,
                    'weekly_discipleship_target_max' => 3,
                    'meeting_link' => 'https://meet.google.com/bcci-ideal-track',
                    'access_notes' => 'Participants are expected to attend all teaching sessions, participate actively in cluster meetings, complete weekly assignments, submit weekly reports, and begin discipling others before the end of the track.',
                ],
            ]
        );

        $resources = [
            [
                'title' => 'BCCI I.D.E.A.L Framework Overview',
                'description' => 'High-level overview of Intention, Development, Establishment, Activation, and Leadership.',
                'external_link' => 'https://example.com/bcci/ideal-framework',
                'type' => 'link',
                'is_downloadable' => true,
            ],
            [
                'title' => 'Weekly Accountability Report Template',
                'description' => 'Template for prayer consistency, Word study, evangelism count, and personal growth insight.',
                'external_link' => 'https://example.com/bcci/weekly-report-template',
                'type' => 'link',
                'is_downloadable' => true,
            ],
        ];

        foreach ($resources as $resource) {
            EventResource::updateOrCreate(
                [
                    'event_id' => $event->id,
                    'title' => $resource['title'],
                ],
                [
                    ...$resource,
                    'uploaded_by' => $organizer->id,
                ]
            );
        }

        $confirmedParticipant = User::firstOrCreate(
            ['email' => 'disciple.leader@bcci.test'],
            [
                'name' => 'Disciple Leader',
                'password' => 'password',
                'phone' => '08000000001',
            ]
        );
        $confirmedParticipant->assignRole('student');

        $event->attendees()->syncWithoutDetaching([
            $confirmedParticipant->id => [
                'status' => EventRegistrationStatus::REGISTERED->value,
                'revoke_count' => 0,
                'created_at' => now()->subDay(),
                'updated_at' => now()->subDay(),
            ],
        ]);
    }

    private function description(): string
    {
        return <<<HTML
<h2>BCCI Discipleship System</h2>
<p><strong>The Multiplication Mandate</strong></p>
<p>This track is designed to raise Christ-like, capacity-driven disciples who walk in light and dominion, reflect BCCI culture, produce results, and multiply themselves intentionally.</p>
<h3>The I.D.E.A.L Journey</h3>
<ul>
<li><strong>Intention</strong>: a conscious decision to grow and take responsibility for spiritual development.</li>
<li><strong>Development</strong>: training in the Word, prayer, discipline, and spiritual systems.</li>
<li><strong>Establishment</strong>: becoming stable, rooted, and consistent in lifestyle and convictions.</li>
<li><strong>Activation</strong>: engaging in evangelism, responsibility, and practical application.</li>
<li><strong>Leadership</strong>: becoming a disciple who leads and raises other disciples.</li>
</ul>
<h3>Core Pillars</h3>
<ul>
<li><strong>Word</strong>: understanding Scripture, living by revelation, and walking in truth.</li>
<li><strong>Life</strong>: Christ-like behavior, discipline, consistency, and integrity.</li>
<li><strong>Power</strong>: prayer, fasting, and hearing God.</li>
<li><strong>Multiplication</strong>: soul winning, discipling others, and influence.</li>
</ul>
<h3>Weekly Flow</h3>
<p>Participants move through a 6-week intensive cycle with weekly central teaching, weekly cluster meetings, practical assignments, and accountability reporting.</p>
<ol>
<li>Week 1: Intention - Identity and Light</li>
<li>Week 2: Development - Prayer Systems</li>
<li>Week 3: Development - Word Systems</li>
<li>Week 4: Establishment - Character and Discipline</li>
<li>Week 5: Activation - Evangelism and Influence</li>
<li>Week 6: Leadership - Discipleship and Activation</li>
</ol>
<p>Before the end of the track, every participant is expected to begin discipling at least 1-3 people.</p>
HTML;
    }
}
