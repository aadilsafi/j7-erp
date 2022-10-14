<?php

namespace App\Http\Requests\units;

use App\Models\Unit;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class fabstoreRequest extends FormRequest
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
            'fab-units.*.name' => 'nullable|string|max:255',
            'fab-units.*.width' => 'required|numeric',
            'fab-units.*.length' => 'required|numeric',
            'fab-units.*.net_area' => 'required|numeric|gt:0',
            'fab-units.*.gross_area' => 'required|numeric|gte:fab-units.*.net_area',
            // 'fab-units.*.price_sqft' => 'required|numeric|gt:0',
            // 'fab-units.*.is_corner' => 'required|boolean|in:0,1',
            // 'fab-units.*.is_facing' => 'required|boolean|in:0,1',
            // 'fab-units.*.facing_id' => 'required_if:is_facing,1|integer',
        ];

        return $rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $ruleMessages = [
            'fab-units.*.corner_id.required_if' => 'The Corner charges field is required when :other is checked.',
            'fab-units.*.facing_id.required_if' => 'The Facing charges field is required when :other is checked.',
            'fab-units.*.gross_area.gte' => 'The Gross Area must be greater than or equal to Net Area.',
        ];

        return $ruleMessages;
    }

    // /**
    //  * Configure the validator instance.
    //  *
    //  * @param  \Illuminate\Validation\Validator  $validator
    //  * @return void
    //  */
    // public function withValidator($validator)
    // {
    //     if (!$validator->fails()) {
    //         $validator->after(function ($validator) {








    //             $typeId = $this->input('type');
    //             if ($typeId != 0) {
    //                 $type = (new Type)->where('id', $typeId)->first();
    //                 if (!$type) {
    //                     $validator->errors()->add('type', 'This type does not exists');
    //                 }
    //             }
    //         });
    //     }
    // }
}
