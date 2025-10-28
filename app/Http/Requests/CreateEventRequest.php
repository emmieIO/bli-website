<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventRequest extends FormRequest {
    /**
    * Determine if the user is authorized to make this request.
    */

    public function authorize(): bool {
        return auth()->user()->checkPermissionTo( 'manage events' );
    }

    /**
    * Get the validation rules that apply to the request.
    *
    * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
    */

    public function rules(): array {
        return [
            'title' => 'required|string|max:255|unique:events,title',
            'theme' => 'required|string|max:100',
            'description' => 'required|string',
            'location' => 'nullable|string|required_if:mode,online,hybrid',
            'attendee_slots' => 'nullable|integer|min:1',
            'program_cover' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'mode' => 'required|string|in:' . implode( ',', array_column( \App\Enums\EventModeEnum::cases(), 'value' ) ),
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'physical_address' => 'nullable|string|max:255, required_if:mode,offline,hybrid',
            'creator_id' => 'required|exists:users,id',
            'is_active' => 'sometimes|boolean',
            'metadata' => 'nullable|array',
            'contact_email' => 'nullable|email|max:255',
            'is_published' => 'sometimes|boolean',
            'is_allowing_application' => 'sometimes|boolean',
            'is_featured' => 'sometimes|boolean',
            'entry_fee' => 'nullable|numeric|min:0|max:999999.99',
        ];
    }

    public function messages(){
        return [
        'title.required' => 'The event title is required.',
        'title.string' => 'The event title must be a string.',
        'title.max' => 'The event title may not be greater than 255 characters.',
        'title.unique' => 'An event with this title already exists.',
        'theme.required' => 'The event theme is required.',
        'theme.string' => 'The event theme must be a string.',
        'theme.max' => 'The event theme may not be greater than 100 characters.',
        'description.required' => 'The event description is required.',
        'description.string' => 'The event description must be a string.',
        'location.required' => 'The event location is required.',
        'location.required_if' => 'The event location is required for online or hybrid events.',
        'attendee_slots.integer' => 'The attendee slots must be an integer.',
        'attendee_slots.min' => 'The attendee slots must be at least 1.',
        'program_cover.image' => 'The program cover must be an image.',
        'program_cover.mimes' => 'The program cover must be a file of type: jpeg, png, jpg, gif, svg.',
        'program_cover.max' => 'The program cover may not be greater than 2MB.',
        'mode.required' => 'The event mode is required.',
        'mode.string' => 'The event mode must be a string.',
        'mode.in' => 'The selected event mode is invalid.',
        'start_date.required' => 'The start date is required.',
        'start_date.date' => 'The start date must be a valid date.',
        'start_date.after_or_equal' => 'The start date must be today or later.',
        'end_date.required' => 'The end date is required.',
        'end_date.date' => 'The end date must be a valid date.',
        'end_date.after_or_equal' => 'The end date must be after or equal to the start date.',
        'physical_address.nullable' => 'The physical address may be null.',
        'physical_address.string' => 'The physical address must be a string.',
        'physical_address.max' => 'The physical address may not be greater than 255 characters.',
        'physical_address.required_if' => 'The physical address is required for offline or hybrid events.',
        'creator_id.required' => 'The creator is required.',
        'creator_id.exists' => 'The selected creator does not exist.',
        'is_active.boolean' => 'The active status must be true or false.',
        'metadata.array' => 'The metadata must be an array.',
        'contact_email.email' => 'The contact email must be a valid email address.',
        'contact_email.max' => 'The contact email may not be greater than 255 characters.',
        'is_published.boolean' => 'The published status must be true or false.',
        'is_allowing_application.boolean' => 'The application allowance must be true or false.',
        'is_featured.boolean' => 'The featured status must be true or false.',
        'entry_fee.numeric' => 'The entry fee must be a number.',
        'entry_fee.min' => 'The entry fee must be at least 0.',
        'entry_fee.max' => 'The entry fee may not be greater than 999999.99.',
        ];
    }

    protected function prepareForValidation(): void {
        $this->merge( [
            'is_active' => $this->has( 'is_active' ),
            'is_published' => $this->has( 'is_published' ),
            'is_allowing_application' => $this->has('is_allowing_application'),
            'is_featured' => $this->has('is_featured')
        ] );
    }
}
