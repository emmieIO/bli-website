<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEventResourceRequest extends FormRequest
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
            "title"=>"required|string",
            "description"=> "nullable|string",
            "file_path" => "nullable|mimes:jpeg,png,jpg,gif,svg,pdf,mp4|max:2048",
            "external_link" => "nullable|url",
            "type" => "required|in:file,link,video,slide",
            "uploaded_by" => "nullable|exists:users,id",
            "is_downloadable" => "sometimes|boolean"
        ];
    }
}
