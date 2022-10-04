<?php

namespace App\Http\Requests\units;

use App\Models\Unit;
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
        $rules = (new Unit())->rules;
        $rules['unit_number'] = [
            'required', 'numeric', 'between:1,' . $this->unit_number_digits, Rule::unique('units')->where('floor_id', decryptParams($this->floor_id))
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
        return (new Unit())->ruleMessages;
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
