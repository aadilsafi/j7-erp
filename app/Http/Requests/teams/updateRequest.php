<?php

namespace App\Http\Requests\teams;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
        return (new Team())->rules;
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function withValidator($validator)
    {
        if (!$validator->fails()) {
            $validator->after(function ($validator) {
                $teamId = $this->input('team');
                if ($teamId != 0) {
                    $team = (new Team)->where('id', $teamId)->first();
                    if (!$team) {
                        $validator->errors()->add('team', 'This team does not exists');
                    }
                }
            });
        }
    }
}
