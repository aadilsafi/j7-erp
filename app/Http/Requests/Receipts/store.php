<?php

namespace App\Http\Requests\Receipts;

use App\Models\Receipt;
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
            'receipts' => 'required|array',
            'receipts.*.unit_id' => 'required|numeric',
            'receipts.*.mode_of_payment' => 'required',
            'receipts.*.amount_in_numbers' => 'required',
            'amount_received' => 'required',
            'receipts.*.other_value' => ' required_if:receipts.*.mode_of_payment,==,Other',
            'receipts.*.cheque_no' => ' required_if:receipts.*.mode_of_payment,==,Cheque',
            'receipts.*.transaction_date' => ' required_if:receipts.*.mode_of_payment,==,Online',
            'receipts.*.online_instrument_no' => ' required_if:receipts.*.mode_of_payment,==,Online',
            'attachment' => 'sometimes',
            'comments' => 'sometimes',
            'receipts.*.bank_name' => 'required_if:receipts.*.mode_of_payment,==,Cheque',
            'receipts.*.bank_branch' => 'required_if:receipts.*.mode_of_payment,==,Cheque',
            'receipts.*.bank_account_number' => 'required_if:receipts.*.mode_of_payment,==,Cheque',
            'receipts.*.bank_contact_number' => 'required_if:receipts.*.mode_of_payment,==,Cheque',
            'receipts.*.bank_branch_code' => 'required_if:receipts.*.mode_of_payment,==,Cheque',
            'receipts.*.bank_address' => 'required_if:receipts.*.mode_of_payment,==,Cheque',
        ];
    }

    public function messages()
    {
        return [
            "receipts.*.unit_id.required" => "Unit id is required.",
            "receipts.*.unit_id.numeric" => "Unit id is required.",
            "receipts.*.mode_of_payment.required" => "Mode of Payment is Required.",
            "receipts.*.amount_in_numbers.required" => "Amount is Required.",
            "amount_received.required" => "Total Amount Received is Required.",
            'receipts.*.other_value' => "Other value is required when Other mode of payment is selected.",
            'receipts.*.cheque_no' => "Cheque number is required when Cheque mode of payment is selected.",
            'receipts.*.transaction_date' => "Transaction Date is required when Online mode of payment is selected.",
            'receipts.*.online_instrument_no' => "Transaction Number is required when Online mode of payment is selected.",
            "attachment" => "Attachment is Required if mode of payment is Cheque or Online or Other.",
            "receipts.*.bank_name" => "Bank Name is Required if mode of payment is Cheque.",
            "receipts.*.bank_branch" => "Bank Branch is Required if mode of payment is Cheque.",
            "receipts.*.bank_account_number" => "Bank Account Number is Required if mode of payment is Cheque.",
            "receipts.*.bank_contact_number" => "Bank Contact Number is Required if mode of payment is Cheque.",
            "receipts.*.bank_branch_code" => "Bank Branch Code is Required if mode of payment is Cheque.",
            "receipts.*.bank_address" => "Bank Address is Required if mode of payment is Cheque.",
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */

    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                $modeOfPayment = $this->input('receipts.*.mode_of_payment');
                $attachment = $this->attachment;
                $amount_received = $this->input('amount_received');
                $amount_in_numbers = $this->input('receipts.*.amount_in_numbers');
                if ($modeOfPayment[0] != 'Cash' && $attachment == null) {
                    $validator->errors()->add('attachment', 'Attachment is Required if mode of payment is Cheque or Online or Other.');
                }
                if($amount_in_numbers[0] >  $amount_received){
                    $validator->errors()->add('invalid_amount', 'Invalid Amount. Amount to be paid should not be greater than Amount Received.');
                }
            });
        }
    }
}
