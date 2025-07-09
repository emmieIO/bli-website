<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->hasPermissionTo('manage events');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
    return [
        "title" => "sometimes|required|string|max:255|unique:events,title," . ($this->event->id ?? 'NULL'),
        "description" => "sometimes|required|string",
        "location" => "sometimes|required|string",
        "program_cover" => "nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048",
        "mode" => "sometimes|required|string|in:" . implode(',', array_column(\App\Enums\EventModeEnum::cases(), 'value')),
        "start_date" => "sometimes|required|date|after_or_equal:now",
        "end_date" => "sometimes|required|date|after_or_equal:start_date",
        "metadata" => "sometimes"
    ];
    }
}
