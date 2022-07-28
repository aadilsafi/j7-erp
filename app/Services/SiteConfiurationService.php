<?php

namespace App\Services;

use App\Models\Site;
use App\Services\Interfaces\SiteConfigurationInterface;
use Exception;
use Illuminate\Support\Str;

class SiteConfiurationService implements SiteConfigurationInterface
{

    public function model()
    {
        return new Site();
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

    public function getAllWithTree()
    {
        $types = $this->model()->all();
        return getTreeData(collect($types), $this->model());
    }

    // Store
    public function store($inputs)
    {
        $data = [
            'name' => $inputs['type_name'],
            'slug' => Str::of($inputs['type_name'])->slug(),
            'parent_id' => $inputs['type'],
        ];
        $type = $this->model()->create($data);
        return $type;
    }

    public function update($inputs, $id)
    {

        $id = decryptParams($id);
        dd($inputs, $id);
        $data = [
            'name' => $inputs['type_name'],
            'slug' => Str::of($inputs['type_name'])->slug(),
            'parent_id' => $inputs['type'],
        ];
        $type = $this->model()->where('id', $id)->update($data);
        return $type;
    }

    // public function destroy($id)
    // {
    //     $id = decryptParams($id);

    //     $types = getTypeLinkedTypes($id);

    //     $typesIDs = array_merge($id, array_column($types, 'id'));

    //     $this->model()->whereIn('id', $typesIDs)->delete();

    //     return true;
    // }
}
