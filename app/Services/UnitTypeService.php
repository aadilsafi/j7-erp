<?php

namespace App\Services;

use App\Models\Type;
use App\Services\Interfaces\UnitTypeInterface;
use Exception;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class UnitTypeService implements UnitTypeInterface
{

    public function model()
    {
        return new Type();
    }

    // Get
    public function getByAll()
    {
        return $this->model()->all();
    }

    public function getById($id)
    {
        return $this->model()->find($id);
    }

    public function getAllWithTree()
    {
        $types = $this->model()->all();
        return getTreeData(collect($types), $this->model());
    }

    // Store
    public function store($site_id, $inputs)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'name' => $inputs['type_name'],
            'slug' => Str::of($inputs['type_name'])->slug(),
            'parent_id' => $inputs['type'],
        ];
        $type = $this->model()->create($data);
        return $type;
    }

    public function update($site_id, $inputs, $id)
    {
        $data = [
            'site_id' => $site_id,
            'name' => $inputs['type_name'],
            'slug' => Str::of($inputs['type_name'])->slug(),
            'parent_id' => $inputs['type'],
        ];
        $type = $this->model()->where('id', $id)->update($data);
        return $type;
    }

    public function destroy($site_id, $id)
    {
        $id = decryptParams($id);

        $types = getLinkedTreeData($this->model(), $id);

        $typesIDs = array_merge($id, array_column($types, 'id'));

        $this->model()->whereIn('id', $typesIDs)->get()->each(function ($row) {
            $row->delete();
        });

        return true;
    }
}
