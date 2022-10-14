<?php

namespace App\Services\CustomFields;

use App\Exceptions\GeneralException;
use App\Models\CustomField;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CustomFieldService implements CustomFieldInterface
{

    public function model()
    {
        return new CustomField();
    }

    public function getAll($site_id, $relationships = [])
    {
        return $this->model()->whereSiteId($site_id)->with($relationships)->get();
    }

    public function getById($site_id, $id, $relationships = [])
    {
        return $this->model()->whereSiteId($site_id)->with($relationships)->find($id);
    }

    public function getAllByModel($site_id, $model, $relationships = [])
    {
        return $this->model()->whereSiteId($site_id)->with($relationships)->whereCustomFieldModel($model)->get();
    }

    public function store($site_id, $inputs)
    {
        $returnValue = DB::transaction(function () use ($site_id, $inputs) {

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

            $data['slug'] = generateSlug($site_id, $data['name'], $this->model());

            if (isset($inputs['values'])) {
                $values = [];

                foreach ($inputs['values'] as $key => $value) {
                    $slug = Str::of($value)->slug()->value();
                    $values[$slug] = $value;
                }

                $data['values'] = $values;
            }
            return $this->model()->create($data);
        });
        return $returnValue;
    }

    public function update($site_id, $id, $inputs)
    {
        $returnValue = DB::transaction(function () use ($site_id, $id, $inputs) {

            $data = [
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
            return $this->model()->find($id)->update($data);
        });
        return $returnValue;
    }

    public function destroy($site_id, $id)
    {
        $returnValue = DB::transaction(function () use ($site_id, $id) {
            $this->model()->whereIn('id', $id)->get()->each(function ($row) {
                $row->delete();
            });

            return true;
        });
        return $returnValue;
    }
}
