<?php

namespace App\Services\CustomFields;

use App\Models\CustomField;
use Illuminate\Support\Str;

class CustomFieldService implements CustomFieldInterface
{

    public function model()
    {
        return new CustomField();
    }

    public function getAll($relationships = [])
    {
        return $this->model()->with($relationships)->get();
    }

    public function getById($id, $relationships = [])
    {
        $id = decryptParams($id);
        return $this->model()->with($relationships)->find($id);
    }

    public function store($site_id, $inputs)
    {
        $data = [
            'site_id' => $site_id,
            'name' => $inputs['name'],
            'type' => $inputs['type'],
            'custom_field_model' => $inputs['custom_field_model'],
            'disabled' => $inputs['disabled'] ? true : false,
            'required' => $inputs['required'] ? true : false,
            'in_table' => $inputs['in_table'] ? true : false,
            'multiple' => $inputs['multiple'] ? true : false,
            'min' => $inputs['min'],
            'max' => $inputs['max'],
            'minlength' => $inputs['minlength'],
            'maxlength' => $inputs['maxlength'],
            'bootstrap_column' => $inputs['bootstrap_column'] ?? 6,
            'order' => $inputs['order'],
        ];

        if (isset($inputs['values'])) {
            $values = [];

            foreach ($inputs['values'] as $key => $value) {
                $slug = Str::of($value)->slug()->value();
                $values[$slug] = $value;
            }

            $data['values'] = $values;
        }

        return $this->model()->create($data);
    }

    public function update($id, $inputs)
    {

        $id = decryptParams($id);

        $data = [
            'name' => $inputs['permission_name'],
            'guard_name' => $inputs['guard_name'],
        ];
        $type = $this->model()->where('id', $id)->update($data);
        return $type;
    }

    public function destroy($id)
    {
        if (!empty($id)) {
            $this->model()->whereIn('id', $id)->delete();
            return true;
        }
        return false;
    }
}
