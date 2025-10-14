<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateSpeakerRequest extends FormRequest
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
            "organization" => ["sometimes"],
            "email" => ["required", 'email', "unique:users,email"],
            "photo" => "required|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "phone" => ["sometimes", 'phone:NG', 'unique:users,phone'],
            'linkedin' => ['nullable', 'regex:/^https?:\/\/(www\.)?linkedin\.com\/in\/[a-zA-Z0-9_-]+\/?$/'],
            'website' => ['nullable', 'url'],
            "password" => ["required", "string", 'confirmed', "min:6"],
            "bio" => ['sometimes'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required.',
            'name.string' => 'Name must be a string.',
            'email.required' => 'Email is required.',
            'email.email' => 'Please provide a valid email address.',
            'email.unique' => 'This email is already taken.',
            'photo.image' => 'Photo must be an image file.',
            'photo.required' => 'Speaker Photo is required',
            'photo.mimes' => 'Photo must be a file of type: jpeg, png, jpg, gif, svg.',
            'photo.max' => 'Photo size must not exceed 2MB.',
            'phone.phone' => 'Please provide a valid Nigerian phone number.',
            'phone.unique' => 'This phone number is already taken.',
            'linkedin.regex' => 'Please provide a valid LinkedIn profile URL.',
            'website.url' => 'Please provide a valid website URL.',
            'password.required' => 'Password is required.',
            'password.string' => 'Password must be a string.',
            'password.confirmed' => 'Password confirmation does not match.',
            'password.min' => 'Password must be at least 6 characters.',
        ];
    }

    public function validated($key = null, $default = null)
    {
        $data = parent::validated($key, $default);

        return [
            'userInfo' => [
                'name' => $data['name'],
                'headline' => $data['headline'],
                'linkedin' => $data['linkedin'] ?? null,
                'website' => $data['website'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'],
                'photo' => $data['photo'],
                'password' => $data['password']
            ],
            'speakerInfo' => [
                'bio' => $data['bio'],
                'organization' => $data['organization'],
            ]
        ];
    }
}
