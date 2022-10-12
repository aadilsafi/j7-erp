<?php

namespace App\Services\CustomFields;

use App\Models\CustomField;
use App\Models\Permission;
use App\Services\CustomFields\PermissionInterface;


class CustomFieldService implements CustomFieldInterface
{

    public function model()
    {
        return new CustomField();
    }

    // Get
    public function getByAll()
    {
        return $this->model()->all();
    }

    public function getById($id)
    {
        $id = decryptParams($id);
        return $this->model()->find($id);
    }

    // Store
    public function store($inputs)
    {
        $data = [
            'name' => $inputs['permission_name'],
            'guard_name' => $inputs['guard_name'],
        ];
        $permission = $this->model()->create($data);
        return $permission;
    }

    public function update($inputs, $id)
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
        $id = decryptParams($id);
        $type = $this->model()->find($id)->delete();
        return $type;
    }

    public function destroySelected($ids)
    {
        if (!empty($ids)) {
            // $ids = decryptParams($ids);
            // dd($ids);
            $this->model()->whereIn('id', $ids)->delete();
            return true;
        }
        return false;
    }
}
