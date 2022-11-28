<?php

namespace App\Http\Requests\Rebateincentive;

use App\Models\BacklistedStakeholder;
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
        $rules = [
            'unit_id' => 'required',
            'rebate_percentage' => 'required',
            'mode_of_payment' => 'required',
            'rebate_total' => 'required',
        ];

        $rules['dealer.cnic'] = ['sometimes', 'required', 'numeric', Rule::unique('stakeholders', 'cnic')->ignore($this->input('stackholder.stackholder_id'))];

        if ($this->input('mode_of_payment') == "Cheque") {
            $rules['bank_name'] = ['required'];
            $rules['bank_branch'] = ['required'];
            $rules['bank_address'] = ['required'];
            $rules['bank_contact_number'] = ['required'];
            $rules['bank_account_number'] = ['required', 'numeric', Rule::unique('banks', 'account_number')->ignore($this->input('bank_id'))];
            $rules['bank_branch_code'] = ['required', 'numeric', Rule::unique('banks', 'branch_code')->ignore($this->input('bank_id'))];
            $rules['cheque_no'] = ['required'];
        }

        if ($this->input('mode_of_payment') == "Online") {
            $rules['bank_name'] = ['required'];
            $rules['bank_branch'] = ['required'];
            $rules['bank_address'] = ['required'];
            $rules['bank_contact_number'] = ['required'];
            $rules['bank_account_number'] = ['required', 'numeric', Rule::unique('banks', 'account_number')->ignore($this->input('bank_id'))];
            $rules['bank_branch_code'] = ['required', 'numeric', Rule::unique('banks', 'branch_code')->ignore($this->input('bank_id'))];
            $rules['transaction_date'] = ['required'];
            $rules['online_instrument_no'] = ['required'];
        }

        if ($this->input('mode_of_payment') == "Other") {
            $rules['other_value'] = ['required'];
        }

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

            $blacklisted = BacklistedStakeholder::where('cnic', $this->input('dealer.cnic'))->first();
            if ($blacklisted) {
                $validator->errors()->add('cnic', 'CNIC is BlackListed.');
            }
        });
        // }
    }

    public function messages()
    {
        return [
            'dealer.cnic.unique' => " CNIC Must Be Unique",
        ];
    }
}
