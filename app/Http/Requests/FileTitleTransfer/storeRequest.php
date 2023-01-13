<?php

namespace App\Http\Requests\FileTitleTransfer;

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
            'transfer_rate' => 'required',
            'payment_due_date' => 'required',
            'amount_remarks' => 'required',
            'amount_to_be_paid' => 'required',
            'attachments' => 'bail|required_if:checkAttachment,1',
            'attachments.*.attachment_label' => 'required_if:checkAttachment,1',
            'attachments.*.image' => 'required_if:checkAttachment,1',
            'individual.mobile_contact' =>'required,unique:stakeholders,mobile_contact,'. $this->input('stackholder.stackholder_id'),
            // 'stackholder.stackholder_id' => 'required',
            // 'stackholder.full_name' => 'required_if:stackholder.stackholder_id,0',
            // 'stackholder.father_name' => 'required_if:stackholder.stackholder_id,0',
            // 'stackholder.contact' => 'required_if:stackholder.stackholder_id,0|string|min:1|max:20',
            // 'stackholder.address' => 'required_if:stackholder.stackholder_id,0|string',
        ];
        // $rules['stackholder.cnic'] = ['required', Rule::unique('stakeholders', 'cnic')->ignore($this->input('stackholder.stackholder_id'))];
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
        $cnic=(array_key_exists('individual',$this->input()) ? $this->input()['individual']['cnic'] : '');
        $blacklisted = BacklistedStakeholder::where('cnic', $cnic)->first();
            if ($blacklisted) {
                $validator->errors()->add('cnic', 'CNIC is BlackListed.');
            }
        });
        // }
    }

    public function messages()
    {
        return [
            'stackholder.cnic.unique' => "Stakeholder Cnic Must be Unique",
            "transfer_rate.required" => "Transfer Charges is Required.",
            "payment_due_date.required" => "Payment Due Date Is Required.",
            'amount_remarks.required' => 'Amount Remark is Required',
            'amount_to_be_paid.required' => 'Transfer Charges to be paid is Required',
            'attachments' => 'Attachments Required if you check Attachments Attached.',
            'attachments.*.attachment_label' => "Attachments Label Required if you check Attachments Attached.",
            'attachments.*.image' => "Attachments Image Required if you check Attachments Attached.",
        ];
    }
}