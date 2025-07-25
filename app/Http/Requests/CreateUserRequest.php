<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateUserRequest extends FormRequest
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
            "email" => ['required', 'email', 'unique:users,email'],
            "password" => ["required", "confirmed", "min:8"],
        ];
    }
}
