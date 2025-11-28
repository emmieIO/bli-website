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
            "file_path" => "nullable|file|mimes:jpeg,png,jpg,gif,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,zip,mp4|max:10048",
            "external_link" => "nullable|url",
            "type" => "required|in:file,link",
            "uploaded_by" => "nullable|exists:users,id",
            "is_downloadable" => "nullable|boolean"
        ];
    }

    public function messages(): array {
        return [
            "title.required" => "The title is required.",
            "title.string" => "The title must be a string.",
            "description.string" => "The description must be a string.",
            "file_path.file" => "The file must be a valid file.",
            "file_path.mimes" => "The file must be a type of: jpeg, png, jpg, gif, svg, pdf, doc, docx, xls, xlsx, ppt, pptx, zip, mp4.",
            "file_path.max" => "The file may not be greater than 10MB.",
            "external_link.url" => "The external link must be a valid URL.",
            "type.required" => "The type is required.",
            "type.in" => "The type must be either file or link.",
            "uploaded_by.exists" => "The selected uploader does not exist.",
            "is_downloadable.boolean" => "The is_downloadable field must be true or false."
        ];
    }
}
