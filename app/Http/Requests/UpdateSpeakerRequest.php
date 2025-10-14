<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSpeakerRequest extends FormRequest
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
            "name" => ['required', "string"],
            "headline" => ['nullable', 'string'],
            "organization" => ["nullable"],
            "email" => ["required", 'email', "unique:users,email," . $this->route('speaker')->user->id],
            "phone" => ["nullable", "phone:NG"],
            "photo" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            'linkedin' => ['nullable', 'regex:/^https?:\/\/(www\.)?linkedin\.com\/in\/[a-zA-Z0-9_-]+\/?$/'],
            'website' => ['nullable', 'url'],
            "bio" => ['nullable']
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'email.required' => 'The email field is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already associated with another speaker.',
            'phone.phone' => 'Please enter a valid Nigerian phone number.',
            'photo.image' => 'The photo must be an image.',
            'photo.mimes' => 'The photo must be a file of type: jpeg, png, jpg, gif, svg.',
            'photo.max' => 'The photo may not be greater than 2MB.',
            'linkedin.regex' => 'Please enter a valid LinkedIn profile URL (e.g., https://linkedin.com/in/yourname)',

        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);
        return [
            'userInfo' => [
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'headline' => $data['headline'] ?? null,
                'linkedin' => $data['linkedin'] ?? null,
                'website' => $data['website'] ?? null,
                'photo' => $data['photo'] ?? null,
            ],

            'speakerProfile' => [
                'organization' => $data['organization'] ?? null,
                'bio' => $data['bio'] ?? null,
            ]
        ];
    }
}
