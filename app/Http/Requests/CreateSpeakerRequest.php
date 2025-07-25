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
            "title" => ['sometimes'],
            "organization" => ["sometimes"],
            "email"=>["required",'email', "unique:speakers,email"],
            "photo" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "phone" => ["sometimes"],
            'linkedin'=> ['sometimes', 'url'],
            'website'=>['sometimes', 'url'],
            "bio" => ['sometimes']
        ];
    }
}
