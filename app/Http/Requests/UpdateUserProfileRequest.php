<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserProfileRequest extends FormRequest
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
            "name" => ["required"],
            "phone" => ['required', 'regex:/^\+?[0-9]{10,15}$/'],
            "email" => ['required', 'email', 'unique:users,email,' . $this->user()->getkey()],
            "current_password" => ["required","min:8"],
        ];
    }
}
