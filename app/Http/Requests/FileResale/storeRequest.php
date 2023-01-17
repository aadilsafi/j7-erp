<?php

namespace App\Http\Requests\FileResale;

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
            'new_resale_rate' => 'required',
            'premium_demand' => 'required',
            'amount_remarks' => 'required',
            'marketing_service_charges' => 'required',
            'doc_number' => 'required|unique:file_resales,doc_no',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'stackholder.cnic.unique' => "Stakeholder Cnic Must be Unique",
            "amount_to_be_refunded.required" => "Amount To Be Refunded is Required.",
            "payment_due_date.required" => "Payment Due Date Is Required.",
            'amount_remarks.required' => 'Amount Remark is Required',
            'amount_profit.required' => 'Profit Amount is Required',
            'attachments' => 'Attachments Required if you check Attachments Attached.',
            'attachments.*.attachment_label' => "Attachments Label Required if you check Attachments Attached.",
            'attachments.*.image' => "Attachments Image Required if you check Attachments Attached.",
            "doc_number.required" => "Document number is  Required.",
            "doc_number.unique" => "Document number is already taken.",
        ];
    }
}
