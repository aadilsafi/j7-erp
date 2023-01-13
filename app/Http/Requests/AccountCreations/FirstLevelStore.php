<?php

namespace App\Http\Requests\AccountCreations;
use Illuminate\Foundation\Http\FormRequest;

class FirstLevelStore  extends FormRequest
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
            'account_code' => 'required||unique:account_heads,code',
            'name' => 'required||unique:account_heads,name',
            'account_type' => 'required',
        ];
    }
}