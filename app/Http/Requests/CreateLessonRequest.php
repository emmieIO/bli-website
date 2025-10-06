<?php

namespace App\Http\Requests;

use App\Enums\LessonType;
use Illuminate\Foundation\Http\FormRequest;

class CreateLessonRequest extends FormRequest
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
            "title"=> "required|string|max:255",
            "type"=> "required|string|in:".implode(',', array_column(LessonType::cases(), 'value')),
            'description'=> 'nullable|string',
            'video_field'=> 'nullable|file|mimes:mp4,mov,avi,wmv|max:51200', // max 50MB
            'content_path'=> 'nullable|file|mimes:pdf|max:10240', // max 10MB
            'link_url'=> 'nullable|url|max:2048',
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('type')) {
            $this->merge([
                'type' => strtolower($this->input('type')),
            ]);
        }
    }

    public function withValidator($validator)
    {
        $validator->sometimes('video_field', 'required', function ($input) {
            return $input->type === 'video';
        });

        $validator->sometimes('content_path', 'required', function ($input) {
            return $input->type === 'pdf';
        });

        $validator->sometimes('link_url', 'required', function ($input) {
            return $input->type === 'link';
        });
    }

    public function messages()
    {
        return [
            'video_field.required' => 'A video file must be provided for the lesson type.',
            'video_field.mimes' => 'The content file must be a video (mp4, mov, avi, wmv).',
            'video_field.file' => 'The video must be a valid file.',
            'video_field.max' => 'The video file may not be greater than 50MB.',
            'content_path.required' => 'A PDF document must be provided for the lesson type.',
            'content_path.mimes' => 'The content file must be a PDF document.',
            'content_path.mimetypes' => 'The content file must be a valid PDF or binary document.',
            'content_path.max' => 'The PDF document may not be greater than 10MB.',
            'link_url.required' => 'An external link must be provided for the lesson type.',
        ];
    }
}
