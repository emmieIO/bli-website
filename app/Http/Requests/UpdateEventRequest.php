<?php

namespace App\Http\Requests;

use App\Enums\EventModeEnum;
use App\Enums\EventStatus;
use App\Enums\Permissions\EventPermissionsEnum;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasAnyPermission([
            EventPermissionsEnum::UPDATE_ANY->value,
            EventPermissionsEnum::PUBLISH->value,
            EventPermissionsEnum::CANCEL->value,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'sometimes|required|string|max:255|unique:events,title,'.($this->event->id ?? 'NULL'),
            'theme' => 'sometimes|string|max:100',
            'description' => 'sometimes|required|string',
            'location' => 'nullable|string|required_if:mode,online,hybrid',
            'attendee_slots' => 'nullable|integer|min:1',
            'program_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:5120',
            'mode' => 'sometimes|required|string|in:'.implode(',', array_column(EventModeEnum::cases(), 'value')),
            'start_date' => [
                'sometimes',
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    if ($this->event && Carbon::parse($this->event->start_date)->isSameDay(Carbon::parse($value))) {
                        return;
                    }
                    if (Carbon::parse($value)->startOfDay()->lt(Carbon::today())) {
                        $fail('The start date must be today or later.');
                    }
                },
            ],
            'end_date' => 'sometimes|required|date|after_or_equal:start_date',
            'physical_address' => 'nullable|string|max:255|required_if:mode,offline,hybrid',
            'creator_id' => 'sometimes|required|exists:users,id',
            'status' => 'nullable|string|in:'.implode(',', EventStatus::values()),
            'is_active' => 'sometimes|boolean',
            'metadata' => 'nullable|array',
            'contact_email' => 'nullable|email|max:255',
            'is_published' => 'nullable|boolean',
            'is_allowing_application' => 'sometimes|boolean',
            'is_featured' => 'nullable|boolean',
            'require_sign_up' => 'sometimes|boolean',
            'entry_fee' => 'nullable|numeric|min:0|max:999999.99',
            'metadata.program_type' => 'nullable|string|in:general_event,discipleship_track',
            'metadata.program_code' => 'nullable|string|max:30',
            'metadata.registration_mode' => 'nullable|string|in:open,selective',
            'metadata.requires_screening' => 'nullable|boolean',
            'metadata.screening_note' => 'nullable|string|max:500',
            'metadata.cohort_duration_weeks' => 'nullable|integer|min:1|max:52',
            'metadata.group_model' => 'nullable|string|max:120',
            'metadata.central_teaching_schedule' => 'nullable|string|max:120',
            'metadata.group_meeting_schedule' => 'nullable|string|max:120',
            'metadata.weekly_prayer_target_minutes' => 'nullable|integer|min:0|max:10080',
            'metadata.weekly_evangelism_target_min' => 'nullable|integer|min:0|max:1000',
            'metadata.weekly_evangelism_target_max' => 'nullable|integer|min:0|max:1000|gte:metadata.weekly_evangelism_target_min',
            'metadata.weekly_discipleship_target_min' => 'nullable|integer|min:0|max:1000',
            'metadata.weekly_discipleship_target_max' => 'nullable|integer|min:0|max:1000|gte:metadata.weekly_discipleship_target_min',
            'metadata.meeting_link' => 'nullable|url|max:2048',
            'metadata.access_notes' => 'nullable|string|max:2000',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The event title is required.',
            'title.unique' => 'An event with this title already exists.',
            'theme.max' => 'The theme may not be greater than 100 characters.',
            'description.required' => 'The event description is required.',
            'location.required_if' => 'Location is required for online or hybrid events.',
            'program_cover.image' => 'The program cover must be an image.',
            'program_cover.mimes' => 'The program cover must be a file of type: jpeg, png, jpg, gif, svg.',
            'program_cover.max' => 'The program cover may not be greater than 5MB.',
            'mode.required' => 'The event mode is required.',
            'mode.in' => 'The selected mode is invalid.',
            'start_date.required' => 'The start date is required.',
            'start_date.date' => 'The start date must be a valid date.',
            'start_date.after_or_equal' => 'The start date must be today or later.',
            'end_date.required' => 'The end date is required.',
            'end_date.date' => 'The end date must be a valid date.',
            'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
            'physical_address.required_if' => 'Physical address is required for offline or hybrid events.',
            'physical_address.max' => 'The physical address may not be greater than 255 characters.',
            'creator_id.required' => 'The creator is required.',
            'creator_id.exists' => 'The selected creator does not exist.',
            'status.in' => 'The selected event status is invalid.',
            'is_active.boolean' => 'The active status must be true or false.',
            'metadata.array' => 'The metadata must be an array.',
            'contact_email.email' => 'The contact email must be a valid email address.',
            'contact_email.max' => 'The contact email may not be greater than 255 characters.',
            'is_published.boolean' => 'The published status must be true or false.',
            'is_allowing_application.boolean' => 'The application status must be true or false.',
            'is_featured.boolean' => 'The featured status must be true or false.',
            'require_sign_up.boolean' => 'The sign-up requirement must be true or false.',
            'entry_fee.numeric' => 'The entry fee must be a number.',
            'entry_fee.min' => 'The entry fee must be at least 0.',
            'entry_fee.max' => 'The entry fee may not be greater than 999999.99.',
            'metadata.program_type.in' => 'The selected program type is invalid.',
            'metadata.registration_mode.in' => 'The selected registration mode is invalid.',
            'metadata.requires_screening.boolean' => 'The screening setting must be true or false.',
            'metadata.cohort_duration_weeks.integer' => 'The cohort duration must be a whole number of weeks.',
            'metadata.weekly_prayer_target_minutes.integer' => 'The weekly prayer target must be a whole number of minutes.',
            'metadata.weekly_evangelism_target_max.gte' => 'The evangelism maximum must be greater than or equal to the evangelism minimum.',
            'metadata.weekly_discipleship_target_max.gte' => 'The discipleship maximum must be greater than or equal to the discipleship minimum.',
            'metadata.meeting_link.url' => 'The meeting link must be a valid URL.',
        ];
    }

    protected function prepareForValidation(): void
    {
        $status = $this->input('status');
        $publishedProvided = $this->exists('is_published');
        $activeProvided = $this->exists('is_active');
        $isPublished = $this->boolean('is_published');
        $isActive = $this->boolean('is_active');

        if (! $status) {
            if ($publishedProvided || $activeProvided) {
                $status = EventStatus::fromLegacyFlags($isPublished, $isActive)->value;
            }
        }

        $this->merge([
            'status' => $status,
            'is_active' => $isActive,
            'is_published' => $isPublished,
            'is_allowing_application' => $this->boolean('is_allowing_application'),
            'is_featured' => $this->boolean('is_featured'),
            'require_sign_up' => $this->exists('require_sign_up')
                ? $this->boolean('require_sign_up')
                : (bool) $this->event?->require_sign_up,
        ]);
    }
}
