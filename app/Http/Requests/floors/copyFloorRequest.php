<?php

namespace App\Http\Requests\floors;

use Illuminate\Foundation\Http\FormRequest;

class copyFloorRequest extends FormRequest
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
            'floor' => 'required',
            'copy_floor_from' => 'required|gt:0|numeric',
            'copy_floor_to' => 'required|lte:50|numeric',
            'shortLabel' => 'required|unique:floors,short_label',
            'shortLabel.*' => 'required|distinct',
        ];
    }
}
