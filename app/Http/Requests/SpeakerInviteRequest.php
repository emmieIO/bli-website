<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpeakerInviteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => ['required', 'exists:events,id'],
            'speaker_id' => ['required', 'exists:speakers,id'],
            'email' => ['nullable', 'email'],
            'suggested_topic' => ['required', 'string', 'max:255'],
            'suggested_duration' => ['required', 'integer', 'min:1'],
            'audience_expectations' => ['required', 'string'],
            'expected_format' => ['required', 'string', 'max:255'],
            'special_instructions' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'event_id.required' => 'Event ID is required. Please refresh and try again.',
            'event_id.exists' => 'Selected event not found.',
            'speaker_id.exists' => 'Speaker does not exist.',
            'email.email' => 'Enter a valid email address.',
            'suggested_topic.required' => 'Please suggest a topic.',
            'suggested_topic.max' => 'Topic must not exceed 255 characters.',
            'suggested_duration.required' => 'Please specify a duration.',
            'suggested_duration.integer' => 'Duration must be a number (minutes).',
            'suggested_duration.min' => 'Duration must be at least one minute.',
            'audience_expectations.required' => 'Please describe audience expectations.',
            'expected_format.required' => 'Please specify the expected format.',
            'expected_format.max' => 'Format must not exceed 255 characters.',
            ];
    }
}

