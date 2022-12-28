<?php

namespace App\Http\Requests\PaymentVoucher;

use App\Models\Receipt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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


        if ($this->input('mode_of_payment') == "Cheque") {
            $rules['bank_name'] = ['required'];
            $rules['bank_branch'] = ['required'];
            $rules['bank_address'] = ['required'];
            $rules['bank_contact_number'] = ['required'];
            if ($this->input('bank_id') == 0) {
                $rules['bank_account_number'] = ['required', 'numeric', Rule::unique('account_heads', 'code')->ignore($this->input('bank_id'))];
                // $rules['bank_branch_code'] = ['required', 'numeric', Rule::unique('banks', 'branch_code')->ignore($this->input('bank_id'))];
            }
            $rules['cheque_no'] = ['required'];
            // $rules['attachment'] = ['required'];

            return  $rules;
        }

        if ($this->input('mode_of_payment') == "Online") {
            $rules['bank_name'] = ['required'];
            $rules['bank_branch'] = ['required'];
            $rules['bank_address'] = ['required'];
            $rules['bank_contact_number'] = ['required'];
            if ($this->input('bank_id') == 0) {
                $rules['bank_account_number'] = ['required', 'numeric', Rule::unique('account_heads', 'code')->ignore($this->input('bank_id'))];
                // $rules['bank_branch_code'] = ['required', 'numeric', Rule::unique('banks', 'branch_code')->ignore($this->input('bank_id'))];
            }
            $rules['transaction_date'] = ['required'];
            $rules['online_instrument_no'] = ['required'];
            // $rules['attachment'] = ['required'];

            return  $rules;
        }

        if ($this->input('mode_of_payment') == "Other") {
            $rules['receipts.*.other_value'] = ['required'];

            return  $rules;
        }
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
            "attachment" => "Attachment is Required if mode of payment is Cheque or Online.",
            "receipts.*.bank_name" => "Bank Name is Required if mode of payment is Cheque or Online.",
            "receipts.*.bank_branch" => "Bank Branch is Required if mode of payment is Cheque or Online.",
            "receipts.*.bank_account_number" => "Bank Account Number is Uniquely Required if mode of payment is Cheque or Online.",
            "receipts.*.bank_contact_number" => "Bank Contact Number is Required if mode of payment is Cheque or Online.",
            "receipts.*.bank_branch_code" => "Bank Branch Code is Uniquely Required if mode of payment is Cheque or Online.",
            "receipts.*.bank_address" => "Bank Address is Required if mode of payment is Cheque or Online.",
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
                if ($modeOfPayment[0] != 'Cash' && $modeOfPayment[0] != 'Other'  && $attachment == null) {
                    $validator->errors()->add('attachment', 'Attachment is Required if mode of payment is Cheque or Online.');
                }
                if ($amount_in_numbers[0] >  $amount_received) {
                    $validator->errors()->add('invalid_amount', 'Invalid Amount. Amount to be paid should not be greater than Amount Received.');
                }
            });
        }
    }
}
