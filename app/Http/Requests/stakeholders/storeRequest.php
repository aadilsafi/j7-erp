<?php

namespace App\Http\Requests\stakeholders;

use App\Models\BacklistedStakeholder;
use App\Models\Stakeholder;
use Illuminate\Foundation\Http\FormRequest;

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
        return (new Stakeholder())->rules;
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
       
        $validator->after(function ($validator) {
        $cnic=(array_key_exists('individual',$this->input()) ? $this->input()['individual']['cnic'] : '');
        $blacklisted = BacklistedStakeholder::where('cnic', $cnic)->first();
            if ($blacklisted) {
                $validator->errors()->add('cnic', 'CNIC is BlackListed.');
            }
        });

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return (new Stakeholder())->ruleMessages;
    }
}