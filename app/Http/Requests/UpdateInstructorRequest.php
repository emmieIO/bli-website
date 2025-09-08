<?php

namespace App\Http\Requests;

use App\Enums\ApplicationStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\File;

class UpdateInstructorRequest extends FormRequest
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
            "name" => "required|string",
            "email" => [
                "required", "email:rfc,dns", "unique:users,email," . $this->route('instructor')->user->id
            ],
            "phone" => "required|phone:NG,US,GB",
            "bio" => "required|string",
            "headline" => "required|string",
            "teaching_history" => "required|string",
            "experience_years" => ['required', 'integer', 'between:0,30'],
            "area_of_expertise" => "required|string",
            "linkedin_url" => "nullable|url",
            "website" => "nullable|url",
            "intro_video_url" => "required|url",
            "resume_path" => ["sometimes", File::types(['docx','pdf'])],
            "application_status" => "required|string|in:" . implode(',', ApplicationStatus::values()),
        ];
    }
}
