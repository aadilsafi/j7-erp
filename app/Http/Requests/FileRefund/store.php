<?php

namespace App\Http\Requests\FileRefund;

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
            'amount_to_be_refunded' => 'required',
            'payment_due_date' => 'required',
            'amount_remarks' => 'required',
            'checkAttachment' => 'sometimes',
            'attachments' => 'bail|required_if:checkAttachment,1',
            'attachments.*.attachment_label' => 'required_if:checkAttachment,1',
            'attachments.*.image' => 'required_if:checkAttachment,1',
        ];
    }

    public function messages()
    {
        return [
            'attachments' => 'Attachments Required if you check Attachments Attached.',
            "amount_to_be_refunded.required" => "Amount To Be Refunded is Required.",
            "payment_due_date.required" => "Payment Due Date Is Required.",
            'amount_remarks.required' => 'Amount Remark is Required',
            'attachments.*.attachment_label' => "Attachments are Required.",
            'attachments.*.image' => "Attachments are Required if you check Attachments Attached.",
        ];
    }
}
