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

    public function getById($site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        return $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ]);
    }

    public function getAllWithTree($site_id)
    {
        $site_id = decryptParams($site_id);
        $additionalCosts = $this->model()->whereSiteId($site_id);
        return getTreeData(collect($additionalCosts), $this->model());
    }

    // Store
    public function store($site_id, $inputs)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'name' => filter_strip_tags($inputs['name']),
            'slug' => Str::of(filter_strip_tags($inputs['slug']))->slug(),
            'parent_id' => filter_strip_tags($inputs['additionalCost']),
            'has_child' => filter_strip_tags($inputs['has_child']),
            'site_percentage' => filter_strip_tags($inputs['site_percentage']),
            'applicable_on_site' => filter_strip_tags($inputs['applicable_on_site']),
            'floor_percentage' => filter_strip_tags($inputs['floor_percentage']),
            'applicable_on_floor' => filter_strip_tags($inputs['applicable_on_floor']),
            'unit_percentage' => filter_strip_tags($inputs['unit_percentage']),
            'applicable_on_unit' => filter_strip_tags($inputs['applicable_on_unit']),
        ];

        // dd($data);
        $type = $this->model()->create($data);
        return $type;
    }

    public function update($site_id, $inputs, $id)
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

    public function destroy($site_id, $id)
    {
        $id = decryptParams($id);

        $types = getTypeLinkedTypes($id);

        $typesIDs = array_merge($id, array_column($types, 'id'));

        $this->model()->whereIn('id', $typesIDs)->delete();

        return true;
    }
}
