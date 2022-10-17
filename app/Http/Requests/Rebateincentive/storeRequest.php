<?php

namespace App\Http\Requests\RebateIncentive;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class storeRequest extends FormRequest
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

        $rules['dealer.cnic'] = ['sometimes','required', 'numeric', Rule::unique('stakeholders','cnic')->ignore($this->input('stackholder.stackholder_id'))];
        return $rules;
    }

    public function messages()
    {
        return [
            'dealer.cnic.unique' => " CNIC Must Be Unique",

        ];
    }
}
