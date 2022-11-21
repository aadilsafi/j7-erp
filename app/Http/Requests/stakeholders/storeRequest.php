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
        // if (!$validator->fails()) {
        $validator->after(function ($validator) {
            if ($this->stakeholder_type == 'C') {
                $parent_id = $this->parent_id;
                // if ($parent_id < 1) {
                //     $validator->errors()->add('parent_id', 'Please select a next of kin');
                // }

                if ($parent_id > 0 && (strlen($this->input('relation')) < 1 || empty($this->input('relation')) || is_null($this->input('relation')))) {
                    $validator->errors()->add('relation', 'Relation is required');
                }
            }
            $blacklisted = BacklistedStakeholder::where('cnic', $this->input('cnic'))->first();
            if ($blacklisted) {
                $validator->errors()->add('cnic', 'CNIC is BlackListed.');
            }
        });
        // }
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
