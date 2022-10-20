<?php

namespace App\Http\Requests\leadSources;

use App\Models\LeadSource;
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
        $rules = (new LeadSource())->rules;
        $rules['lead_source_name'] = 'required|string|unique:lead_sources,name,' . decryptParams($this->id);
        return $rules;
    }
}
