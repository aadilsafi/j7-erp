<?php

namespace App\Http\Requests\FileTitleTransfer;

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
            'stackholder.stackholder_id' => 'required',
            'stackholder.full_name' => 'required|string|min:1|max:50',
            'stackholder.father_name' => 'required|string|min:1|max:50',
            'stackholder.occupation' => 'required|string|min:1|max:50',
            'stackholder.designation' => 'required|string|min:1|max:50',
            'stackholder.ntn' => 'required|numeric',
            'stackholder.contact' => 'required|string|min:1|max:20',
            'stackholder.address' => 'required|string',
        ];
        $rules['stackholder.cnic'] = ['required', 'numeric', Rule::unique('stakeholders','cnic')->ignore($this->input('stackholder.stackholder_id'))];
        return $rules;
    }

    public function messages()
    {
        return [
            'stackholder.cnic.unique' => "Stakeholder Cnic Must be Unique",
            "transfer_rate.required" => "Transfer Charges is Required.",
            "payment_due_date.required" => "Payment Due Date Is Required.",
            'amount_remarks.required' => 'Amount Remark is Required',
            'amount_to_be_paid.required' => 'Transfer Charges to be paid is Required',
            'attachments' => 'Attachments Required if you check Attachements Attached.',
            'attachments.*.attachment_label' => "Attachments Label Required if you check Attachements Attached.",
            'attachments.*.image' => "Attachments Image Required if you check Attachements Attached.",
        ];
    }
}
