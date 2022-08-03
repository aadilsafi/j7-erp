<?php

namespace App\Services;

use App\Models\AdditionalCost;
use App\Models\UserBatch;
use App\Services\Interfaces\UserBatchInterface;
use Exception;
use Illuminate\Support\Str;

class UserBatchService implements UserBatchInterface
{

    public function model()
    {
        return new UserBatch();
    }

    // Get
    public function getByAll()
    {
        return $this->model()->all();
    }

    public function getById($site_id, $id)
    {
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
        $additionalCost = $this->model()->create($data);
        return $additionalCost;
    }

    public function update($site_id, $inputs, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        $data = [
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

        $additionalCost = $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ])->update($data);

        return $additionalCost;
    }

    public function destroy($site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);
        $additionalCosts = getLinkedTreeData($this->model(), $id);

        $additionalCostsIDs = array_merge($id, array_column($additionalCosts, 'id'));
        // dd($additionalCostsIDs);
        $this->model()->whereIn('id', $additionalCostsIDs)->delete();

        return true;
    }
}
