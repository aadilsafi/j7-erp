<?php

namespace App\Http\Requests\CustomFields;

use App\Models\CustomField;
use App\Utils\Enums\CustomFieldsEnum;
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
        $data = [
            'name' => ['required', 'string', 'between:1,120'],
            'type' => ['required', 'string', 'between:1,50', 'in:' . implode(',', CustomFieldsEnum::values())],
            'values' => ['nullable', 'array'],
            'custom_field_model' => ['required', 'string'],
            'disabled' => ['nullable', 'boolean'],
            'required' => ['nullable', 'boolean'],
            'in_table' => ['nullable', 'boolean'],
            'multiple' => ['nullable', 'boolean'],
            'min' => ['nullable', 'numeric'],
            'max' => ['nullable', 'numeric'],
            'minlength' => ['nullable', 'numeric'],
            'maxlength' => ['nullable', 'numeric'],
            'bootstrap_column' => ['nullable', 'numeric', 'between:1,12'],
            'order' => ['nullable', 'numeric', Rule::unique('custom_fields')->where('site_id', decryptParams($this->site_id))->where('custom_field_model', $this->custom_field_model)],
        ];

        return $data;
    }
}
