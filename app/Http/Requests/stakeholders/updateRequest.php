<?php

namespace App\Http\Requests\stakeholders;

use App\Models\BacklistedStakeholder;
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
        $rules =  (new Stakeholder())->rules;
        $rules['company.registration'] .= ',' . (int)decryptParams($this->id);
        $rules['individual.individual_email'] .= ',' . decryptParams($this->id);
        $rules['individual.office_email'] .= ',' . decryptParams($this->id);
        $rules['company.office_email'] .= ',' . decryptParams($this->id);
        $rules['company.company_ntn'] .= ',' . decryptParams($this->id);
        $rules['company.strn'] .= ',' . decryptParams($this->id);
        $rules['individual.ntn'] .= ',' . decryptParams($this->id);
        $rules['individual.passport_no'] .= ',' . decryptParams($this->id);
        $rules['individual.cnic'] .= ',' . decryptParams($this->id);
        $rules['individual.mobile_contact'] .= ',' . (int)decryptParams($this->id);
        $rules['individual.office_contact'] .= ',' . decryptParams($this->id);
        $rules['stakeholder_type'] = 'array';
        unset($rules['stakeholder_type']);

        return $rules;
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
            $parent_id = $this->parent_id;

            if ($parent_id > 0 && (strlen($this->input('relation')) < 1 || empty($this->input('relation')) || is_null($this->input('relation')))) {
                $validator->errors()->add('relation', 'Relation is required');
            }
           $cnic=(array_key_exists('individual',$this->input()) ? $this->input()['individual']['cnic'] : '');
             $blacklisted = BacklistedStakeholder::where('cnic', $cnic)->first();
            if ($blacklisted) {
                $validator->errors()->add('cnic', 'CNIC is BlackListed.');
            }

        });
        //
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