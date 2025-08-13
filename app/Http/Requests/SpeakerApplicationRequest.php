<?php

namespace App\Http\Requests;

use App\Models\SpeakerApplication;
use App\Services\Speakers\SpeakerApplicationService;
use Illuminate\Foundation\Http\FormRequest;

class SpeakerApplicationRequest extends FormRequest
{
    protected ?SpeakerApplication $existingApplication = null;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        $event = $this->route('event');

        if ($event) {
            $this->existingApplication = app(SpeakerApplicationService::class)->getExistingApplication($event);
        }

    }



    public function photoRule()
    {
        $rule = ['required', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'];

        if ($this->existingApplication && $this->existingApplication->speaker->photo) {
            $rule[0] = 'nullable';
        }
        return $rule;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "name" => ['required', "string"],
            "title" => ['required', 'string'],
            "organization" => ["required", 'string'],
            "photo" => $this->photoRule(),
            'linkedin' => ['sometimes', 'url'],
            'website' => ['sometimes', 'url'],
            "bio" => ['required', 'string', 'max:2000'],
            "topic_title" => ['required', "string"],
            "topic_description" => ['required', "string"],
            "session_format" => [
                'required',
                'string',
                'in:' . implode(',', array_map(fn($case) => $case->value, \App\Enums\SessionFormat::cases()))
            ],
            'notes' => ['nullable', 'string', 'max:1000']
        ];
    }
    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'Please enter your name.',
            'name.string' => 'The name must be a valid string.',
            'title.required' => 'Please enter your professional title.',
            'title.string' => 'The title must be a valid string.',
            'organization.required' => 'Please enter your organization.',
            'organization.string' => 'The organization must be a valid string.',
            'email.required' => 'Please provide your email address.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email address has already been used.',
            'photo.image' => 'The photo must be an image file.',
            'bio.required' => 'Please provide your biography.',
            'bio.string' => 'The biography must be a valid string.',
            'bio.max' => 'Your biography may not exceed 2000 characters.',
            'topic_title.required' => 'Please enter the title of your topic.',
            'topic_title.string' => 'The topic title must be a valid string.',
            'topic_description.required' => 'Please provide a description for your topic.',
            'topic_description.string' => 'The topic description must be a valid string.',
            'session_format.required' => 'Please select a session format.',
            'session_format.string' => 'The session format must be a valid string.',
            'notes.string' => 'Additional notes must be a valid string.',
            'notes.max' => 'Additional notes may not exceed 1000 characters.',
            'photo.mimes' => 'The photo must be a file of type: jpeg, png, jpg, gif, svg.',
            'photo.max' => 'The photo may not be greater than 2MB.',
            'phone.required' => 'Please provide your phone number.',
            'phone.phone' => 'Please provide a valid Nigerian phone number.',
            'linkedin.url' => 'Please provide a valid LinkedIn URL.',
            'website.url' => 'Please provide a valid website URL.',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        return [
            'speakerInfo' => [
                'name' => $data['name'] ?? null,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone,
                'title' => $data['title'] ?? null,
                'organization' => trim($data['organization']) ?? null,
                'photo' => $data['photo'] ?? null,
                'linkedin' => $data['linkedin'] ?? null,
                'website' => $data['website'] ?? null,
                'bio' => $data['bio'] ?? null,
            ],
            'applicationInfo' => [
                'topic_title' => $data['topic_title'] ?? null,
                'topic_description' => $data['topic_description'] ?? null,
                'session_format' => $data['session_format'] ?? null,
                'notes' => $data['notes'] ?? null,
            ],
        ];
    }

}
