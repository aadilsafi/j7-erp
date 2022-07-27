<?php

namespace App\Http\Requests\additionalCosts;

use App\Models\AdditionalCost;
use Illuminate\Foundation\Http\FormRequest;

class updateRequest extends FormRequest
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
        $rules = (new AdditionalCost())->rules;
        $rules['slug'] = 'required|alpha_dash|min:1|max:255|unique:additional_costs,slug,' . decryptParams($this->id);
        // dd($rules);
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
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                $additionalCost = $this->input('additionalCost');
                if ($additionalCost != 0) {
                    $additionalCost = (new AdditionalCost())->where('id', $additionalCost)->first();
                    if (!$additionalCost) {
                        $validator->errors()->add('additionalCost', 'This additional cost does not exists');
                    }
                }
            });
        }
    }
}
