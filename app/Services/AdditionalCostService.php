<?php

namespace App\Services;

use App\Models\AdditionalCost;
use App\Services\Interfaces\AdditionalCostInterface;
use Exception;
use Illuminate\Support\Str;

class AdditionalCostService implements AdditionalCostInterface
{

    public function model()
    {
        return new AdditionalCost();
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
        $additionalCosts = $this->model()->all();
        return getTreeData(collect($additionalCosts), $this->model());
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

        $data = [
            'name' => $inputs['type_name'],
            'slug' => Str::of($inputs['type_name'])->slug(),
            'parent_id' => $inputs['type'],
        ];
        $type = $this->model()->where('id', $id)->update($data);
        return $type;
    }

    public function destroy($id)
    {
        $id = decryptParams($id);

        $types = getTypeLinkedTypes($id);

        $typesIDs = array_merge($id, array_column($types, 'id'));

        $this->model()->whereIn('id', $typesIDs)->delete();

        return true;
    }
}
