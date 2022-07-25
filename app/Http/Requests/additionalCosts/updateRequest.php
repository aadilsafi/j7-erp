<?php

namespace App\Http\Requests\additionalCosts;

use App\Models\Type;
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
        return (new Type())->requestRules;
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
                $typeId = $this->input('type');
                if ($typeId != 0) {
                    $type = (new Type)->where('id', $typeId)->first();
                    if (!$type) {
                        $validator->errors()->add('type', 'This type does not exists');
                    }
                }
            });
        }
    }
}
