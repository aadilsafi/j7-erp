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
            'receipts.*.other_value' => ' required_if:receipts.*.mode_of_payment,==,Other',
            'receipts.*.cheque_no' => ' required_if:receipts.*.mode_of_payment,==,Cheque',
            'receipts.*.transaction_date' => ' required_if:receipts.*.mode_of_payment,==,Online',
            'receipts.*.online_instrument_no' => ' required_if:receipts.*.mode_of_payment,==,Online',

        ];
    }

    public function messages()
    {
        return [
            "receipts.*.unit_id.required" => "Unit id is required.",
            "receipts.*.unit_id.numeric" => "Unit id is required.",
            "receipts.*.mode_of_payment.required" => "Mode of Payment is Required.",
            "receipts.*.amount_in_numbers.required" => "Amount is Required.",
            'receipts.*.other_value' => "Other value is required when Other mode of payment is selected.",
            'receipts.*.cheque_no' => "Cheque number is required when Cheque mode of payment is selected.",
            'receipts.*.transaction_date' => "Transaction Date is required when Online mode of payment is selected.",
            'receipts.*.online_instrument_no' => "Transaction Number is required when Online mode of payment is selected.",
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

    }
}
