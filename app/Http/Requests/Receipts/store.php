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
