<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->checkPermissionTo('manage events');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title" => "required|string|max:255|unique:events,title",
            "description"=>"required|string",
            "location"=> "required|string",
            "program_cover" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
            "mode" => "required|string|in:" . implode(',', array_column(\App\Enums\EventModeEnum::cases(), 'value')),
            "start_date" => "required|date|after_or_equal:now",
            "end_date" => "required|date|after_or_equal:start_date",
            "metadata" => "sometimes"
        ];
    }
}
