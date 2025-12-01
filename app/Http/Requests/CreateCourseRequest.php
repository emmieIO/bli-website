<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use App\Enums\CourseLevel;
use App\Models\Course;
use Illuminate\Foundation\Http\FormRequest;

class CreateCourseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->user()->can('create', Course::class);
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'is_free' => filter_var($this->is_free, FILTER_VALIDATE_BOOLEAN) ? 1 : 0,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isUpdating = $this->isMethod('PUT') || $this->isMethod('PATCH') || $this->has('_method');

        return [
            "title" => ["required", "string", "max:255"],
            "subtitle" => ["nullable", "string", "max:500"],
            "description" => ["required", "string", "min:100"],
            "language" => ["required", "string", "max:50"],
            "thumbnail" => [$isUpdating ? "nullable" : "required", "file", "mimes:jpg,jpeg,png,webp", "max:2048"],
            "preview_video" => ["nullable", "file", "mimes:mp4,mov,avi,wmv", "max:51200"], // 50MB max
            "level" => ["required", "string", "in:" . implode(',', array_column(CourseLevel::cases(), 'value'))],
            "category_id" => ["required", "exists:categories,id"],
            "is_free" => ["required", "boolean"],
            "price" => ["required_if:is_free,0", "numeric", "min:0", "max:9999.99"],
        ];
    }

    public function messages(): array
    {
        $isUpdating = $this->isMethod('PUT') || $this->isMethod('PATCH') || $this->has('_method');

        return [
            'title.required' => 'Course title is required.',
            'title.max' => 'Course title cannot exceed 255 characters.',
            'subtitle.max' => 'Course subtitle cannot exceed 500 characters.',
            'description.required' => 'Course description is required.',
            'description.min' => 'Course description must be at least 100 characters.',
            'language.required' => 'Course language is required.',
            'thumbnail.required' => $isUpdating ? 'Course thumbnail image is optional when updating.' : 'Course thumbnail image is required.',
            'thumbnail.mimes' => 'Thumbnail must be a JPG, JPEG, PNG, or WEBP file.',
            'thumbnail.max' => 'Thumbnail file size cannot exceed 2MB.',
            'preview_video.mimes' => 'Preview video must be MP4, MOV, AVI, or WMV format.',
            'preview_video.max' => 'Preview video file size cannot exceed 50MB.',
            'level.required' => 'Course difficulty level is required.',
            'category_id.required' => 'Course category is required.',
            'category_id.exists' => 'Selected category is invalid.',
            'is_free.required' => 'Please specify if this is a free course.',
            'price.required_if' => 'Price is required for paid courses.',
            'price.numeric' => 'Price must be a valid number.',
            'price.max' => 'Price cannot exceed $9,999.99.',
        ];
    }
}
