<?php

namespace App\Http\Requests\users;

use App\Models\BacklistedStakeholder;
use App\Models\User;
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
        return (new User())->rules;
    }

    public function withValidator($validator)
    {
        // if (!$validator->fails()) {
        $validator->after(function ($validator) {

            $blacklisted = BacklistedStakeholder::where('cnic', $this->input('cnic'))->first();
            if ($blacklisted) {
                $validator->errors()->add('cnic', 'CNIC is BlackListed.');
            }
        });
        // }
    }
    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    // public function messages()
    // {
    //     return (new User())->ruleMessages;
    // }
}
