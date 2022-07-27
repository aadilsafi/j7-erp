<?php

namespace App\Http\Requests\additionalCosts;

use App\Models\AdditionalCost;
use App\Models\Type;
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
        return (new AdditionalCost())->rules;
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
