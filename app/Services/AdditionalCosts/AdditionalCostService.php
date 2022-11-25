<?php

namespace App\Services\AdditionalCosts;

use App\Models\AdditionalCost;
use App\Services\AdditionalCosts\AdditionalCostInterface;
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
        ])->first();
    }

    public function getAllWithTree($site_id)
    {
        $site_id = decryptParams($site_id);
        $additionalCosts = $this->model()->whereSiteId($site_id)->get();
        return getTreeData(collect($additionalCosts), $this->model());
    }

    // Store
    public function store($site_id, $inputs, $customFields)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'name' => filter_strip_tags($inputs['name']),
            'slug' => Str::of(filter_strip_tags($inputs['name']))->slug(),
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

        foreach ($customFields as $key => $value) {
            $additionalCost->CustomFieldValues()->updateOrCreate([
                'custom_field_id' => $value->id,
            ], [
                'value' => $inputs[$value->slug],
            ]);
        }
        return $additionalCost;
    }

    public function update($site_id, $inputs, $id, $customFields)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        $data = [
            'name' => filter_strip_tags($inputs['name']),
            'slug' => Str::of(filter_strip_tags($inputs['name']))->slug(),
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
        ])->first();
        $additionalCost->update($data);

        foreach ($customFields as $key => $value) {
            $additionalCost->CustomFieldValues()->updateOrCreate([
                'custom_field_id' => $value->id,
            ], [
                'value' => $inputs[$value->slug],
            ]);
        }
        return $additionalCost;
    }

    public function destroy($site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);
        $additionalCosts = getLinkedTreeData($this->model(), $id);

        $additionalCostsIDs = array_merge($id, array_column($additionalCosts, 'id'));

        $this->model()->whereIn('id', $additionalCostsIDs)->get()->each(function ($row) {
            $row->delete();
        });

        return true;
    }
}
