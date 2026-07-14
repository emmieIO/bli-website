<?php

namespace App\Http\Requests\Concerns;

use Illuminate\Validation\Validator;

trait NormalizesEventPayload
{
    /**
     * Canonicalize multipart form values before they reach validation or JSON storage.
     * Invalid numeric values are deliberately preserved so the validator can reject them.
     *
     * @return array<string, mixed>
     */
    protected function normalizedEventFields(bool $useAuthenticatedCreator = false): array
    {
        $normalized = [];
        $description = $this->input('description');

        if ($this->exists('description') && is_string($description)) {
            $normalized['description'] = trim(strip_tags(html_entity_decode($description))) === ''
                ? ''
                : $description;
        }

        $mode = $this->input('mode');

        if ($mode === 'online') {
            $normalized['physical_address'] = null;
        } elseif ($mode === 'offline') {
            $normalized['location'] = null;
        }

        if ($this->exists('metadata') && is_array($this->input('metadata'))) {
            $metadata = $this->input('metadata');
            $metadata['requires_screening'] = $this->boolean('metadata.requires_screening');

            foreach ($this->eventMetadataIntegerFields() as $field) {
                $value = $metadata[$field] ?? null;

                if ($value !== null && is_numeric($value)) {
                    $metadata[$field] = (int) $value;
                }
            }

            if (($metadata['program_type'] ?? 'general_event') !== 'discipleship_track') {
                foreach ($this->discipleshipOnlyMetadataFields() as $field) {
                    $metadata[$field] = null;
                }
            }

            if (! $metadata['requires_screening'] && ($metadata['registration_mode'] ?? 'open') === 'open') {
                $metadata['screening_note'] = null;
            }

            $normalized['metadata'] = $metadata;
        }

        if ($useAuthenticatedCreator) {
            $normalized['creator_id'] = $this->user()?->id;
        }

        return $normalized;
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->boolean('require_sign_up', true) && (float) $this->input('entry_fee', 0) > 0) {
                $validator->errors()->add(
                    'entry_fee',
                    'Paid events must require account sign-up so payment can be linked to an attendee.'
                );
            }
        });
    }

    /** @return list<string> */
    private function eventMetadataIntegerFields(): array
    {
        return [
            'cohort_duration_weeks',
            'weekly_prayer_target_minutes',
            'weekly_evangelism_target_min',
            'weekly_evangelism_target_max',
            'weekly_discipleship_target_min',
            'weekly_discipleship_target_max',
        ];
    }

    /** @return list<string> */
    private function discipleshipOnlyMetadataFields(): array
    {
        return [
            'cohort_duration_weeks',
            'group_model',
            'central_teaching_schedule',
            'group_meeting_schedule',
            'weekly_prayer_target_minutes',
            'weekly_evangelism_target_min',
            'weekly_evangelism_target_max',
            'weekly_discipleship_target_min',
            'weekly_discipleship_target_max',
        ];
    }
}
