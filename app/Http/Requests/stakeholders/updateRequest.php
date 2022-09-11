<?php

namespace App\Http\Requests\stakeholders;

use App\Models\Stakeholder;
use Illuminate\Foundation\Http\FormRequest;

class updateRequest extends FormRequest
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
            'full_name' => 'required|string|min:1|max:50',
            'father_name' => 'required|string|min:1|max:50',
            'occupation' => 'required|string|min:1|max:50',
            'designation' => 'required|string|min:1|max:50',
            'cnic' => 'required|string|min:1|max:15',
            'contact' => 'required|string|min:1|max:20',
            'address' => 'required|string',
            'parent_id' => 'nullable|numeric',
            'relation' => 'required_with:parent_id',
            'attachment' => 'required|min:2',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {

    }
}
