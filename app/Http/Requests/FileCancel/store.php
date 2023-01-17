<?php

namespace App\Http\Requests\FileCancel;

use Illuminate\Foundation\Http\FormRequest;

class store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'checkAttachment' => 'sometimes',
            'attachments' => 'bail|required_if:checkAttachment,1',
            'attachments.*.attachment_label' => 'Bail|required_if:checkAttachment,1',
            'attachments.*.image' => 'bail|required_if:checkAttachment,1',
            'doc_number' => 'required|unique:file_cancellations,doc_no',
        ];
    }

    public function messages()
    {
        return [
            'attachments' => 'Attachments Required if you check Attachments Attached.',
            'attachments.*.attachment_label' => "Attachments Label Required if you check Attachments Attached.",
            'attachments.*.image' => "Attachments Image Required if you check Attachments Attached.",
            "doc_number.required" => "Document number is  Required.",
            "doc_number.unique" => "Document number is already taken.",
        ];
    }
}
