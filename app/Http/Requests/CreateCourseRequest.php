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
        return auth()->user()->can('course-create', Course::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "title"=> ["required","string","max:255"],
            "description"=> ["required","string"],
            "thumbnail_path"=> ["required","file","mimes:jpg,jpeg,png,gif,webp,svg","max:1024"],
            "level"=> ["required",'string', 'in:'.implode(',', array_column(CourseLevel::cases(), 'value'))],
            'category_id'=> ['required','exists:categories,id'],
            "price"=> ["required","numeric","min:0"],
            'status' => ['nullable','string','in:'.implode(',', array_column(ApplicationStatus::cases(), 'value'))],
        ];
    }
}
