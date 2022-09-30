<?php

namespace App\Http\Requests\File;

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
            'application_form.registration_no' => 'required',
            'application_form.application_no' => 'required',
            'application_form.photo' => 'required',
        ];
    }

    public function messages()
    {
        return [
            "application_form.registration_no.required" => "Registration Number Required.",
            "application_form.application_no" => "Application Number Required.",
            'application_form.photo' => 'Applicant Picture  Required',
        ];
    }
}
