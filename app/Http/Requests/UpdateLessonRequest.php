<?php

namespace App\Http\Requests;

use App\Enums\LessonType;
use Illuminate\Foundation\Http\FormRequest;

class UpdateLessonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'type' => 'required|string|in:' . implode(',', array_column(LessonType::cases(), 'value')),
            'description' => 'nullable|string',
            'video_field' => 'nullable|file|mimes:mp4,mov,avi,wmv,mkv|max:512000',
            'content_path' => 'nullable|file|mimes:pdf|max:10240',
            'link_url' => 'nullable|url|max:2048',
            'is_preview' => 'nullable|boolean',
            'has_instruction' => 'nullable|boolean',
            'assignment_instructions' => 'nullable|string|max:2000',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('type')) {
            $this->merge([
                'type' => strtolower((string) $this->input('type')),
            ]);
        }
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('link_url', 'required', function ($input) {
            return $input->type === 'link';
        });
    }
}
