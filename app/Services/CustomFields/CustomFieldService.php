<?php

namespace App\Services\CustomFields;

use App\Models\CustomField;

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

    public function store($inputs)
    {
        $data = [
            'name' => $inputs['permission_name'],
            'guard_name' => $inputs['guard_name'],
        ];
        $permission = $this->model()->create($data);
        return $permission;
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
