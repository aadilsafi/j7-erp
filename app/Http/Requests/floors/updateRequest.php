<?php

namespace App\Http\Requests\floors;

use App\Models\Floor;
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
        $rules = (new Floor())->rules;
        $rules['short_label'] = 'required|string|max:5|unique:floors,short_label,' . decryptParams($this->id);
        return $rules;
    }
}
