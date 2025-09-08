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

        return[
            "name" => ['required', "string"],
            "title" => ['sometimes'],
            "organization" => ["sometimes"],
            "email"=>["required",'email', "unique:speakers,email,".$this->route('speaker')->id],
            "phone" => ["sometimes", "phone:NG"],
            "photo" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            'linkedin'=> ['sometimes'],
            'website'=>['sometimes'],
            "bio" => ['sometimes']
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
        ] ;
    }
}
