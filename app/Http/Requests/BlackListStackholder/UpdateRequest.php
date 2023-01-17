<?php

namespace App\Http\Requests\BlackListStackholder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\BacklistedStakeholder;
class UpdateRequest extends FormRequest
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

        $id=decryptParams($this->id);

             $rules =  (new BacklistedStakeholder())->rules;
             $rules['cnic'] = ['required', Rule::unique('backlisted_stakeholders')->ignore($id)];
             $rules['country'] = ['required'];
             $rules['district'] = ['required'];
             $rules['province'] = ['required'];

            return $rules;

    }
}
