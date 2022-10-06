<?php

namespace App\Http\Requests\FileBuyBack;

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
        return [
            'amount_to_be_refunded' => 'required',
            'payment_due_date' => 'required',
            'amount_remarks' => 'required',
            'amount_profit' => 'required',
            'attachments' => 'bail|required_if:checkAttachment,on',
            'attachments.*.attachment_label' => 'required_if:checkAttachment,on',
            'attachments.*.image' => 'required_if:checkAttachment,on',
        ];
    }

    public function messages()
    {
        return [
            "amount_to_be_refunded.required" => "Amount To Be Refunded is Required.",
            "payment_due_date.required" => "Payment Due Date Is Required.",
            'amount_remarks.required' => 'Amount Remark is Required',
            'amount_profit.required' => 'Profit Amount is Required',
            'attachments' => 'Attachments Required if you check Attachements Attached.',
            'attachments.*.attachment_label' => "Attachments Label Required if you check Attachements Attached.",
            'attachments.*.image' => "Attachments Image Required if you check Attachements Attached.",
        ];
    }
}
