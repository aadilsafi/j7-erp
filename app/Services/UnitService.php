<?php

namespace App\Services;

use App\Jobs\units\MainUnitJob;
use App\Models\Floor;
use App\Models\Unit;
use App\Services\Interfaces\UnitInterface;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

class UnitService implements UnitInterface
{

    public function model()
    {
        return new Unit();
    }

    // Get
    public function getByAll($site_id, $floor_id)
    {
        $site_id = decryptParams($site_id);
        $floor_id = decryptParams($floor_id);

        return $this->model()->where('floor_id', $floor_id)->get();
    }

    public function getById($site_id, $floor_id, $id)
    {
        $site_id = decryptParams($site_id);
        $floor_id = decryptParams($floor_id);
        $id = decryptParams($id);

        return $this->model()->where([
            'floor_id' => $floor_id,
            'id' => $id,
        ])->first();
    }

    // Store
    public function store($site_id, $floor_id, $inputs, $isUnitActive = true)
    {
        $site_id = decryptParams($site_id);
        $floor_id = decryptParams($floor_id);

        $totalPrice = floatval($inputs['gross_area']) * floatval($inputs['price_sqft']);

        $unitNumberDigits = (new Floor())->find($floor_id)->site->siteConfiguration->unit_number_digits;

        $data = [
            'floor_id' => $floor_id,
            'name' => filter_strip_tags($inputs['name'] ?? ''),
            'width' => filter_strip_tags($inputs['width']),
            'length' => filter_strip_tags($inputs['length']),
            'unit_number' => filter_strip_tags($inputs['unit_number']),
            'floor_unit_number' => $floor_id . Str::padLeft($inputs['unit_number'], $unitNumberDigits, '0'),
            'net_area' => filter_strip_tags($inputs['net_area']),
            'gross_area' => filter_strip_tags($inputs['gross_area']),
            'price_sqft' => filter_strip_tags($inputs['price_sqft']),
            'total_price' => $totalPrice,
            'is_corner' => filter_strip_tags($inputs['is_corner']),
            'corner_id' => isset($inputs['corner_id']) ? filter_strip_tags($inputs['corner_id']) : null,
            'is_facing' => filter_strip_tags($inputs['is_facing']),
            'facing_id' => isset($inputs['facing_id']) ? filter_strip_tags($inputs['facing_id']) : null,
            'type_id' => filter_strip_tags($inputs['type_id']),
            'status_id' => filter_strip_tags($inputs['status_id']),
            'active' => $isUnitActive,
        ];

        // dd($data);

        $floor = $this->model()->create($data);
        return $floor;
    }

    public function storeInBulk($site_id, $floor_id, $inputs, $isUnitActive = false)
    {

        $inputs['total_price'] = floatval($inputs['gross_area']) * floatval($inputs['price_sqft']);

        $batch = Bus::batch([
            new MainUnitJob($site_id, $floor_id, $inputs, $isUnitActive),
        ])->dispatch();

        return $batch;
    }

    public function update($site_id, $floor_id, $id, $inputs)
    {
        $site_id = decryptParams($site_id);
        $floor_id = decryptParams($floor_id);
        $id = decryptParams($id);

        $totalPrice = floatval($inputs['gross_area']) * floatval($inputs['price_sqft']);

        $data = [
            'name' => filter_strip_tags($inputs['name'] ?? ''),
            'width' => filter_strip_tags($inputs['width']),
            'length' => filter_strip_tags($inputs['length']),
            'net_area' => filter_strip_tags($inputs['net_area']),
            'gross_area' => filter_strip_tags($inputs['gross_area']),
            'price_sqft' => filter_strip_tags($inputs['price_sqft']),
            'total_price' => $totalPrice,
            'is_corner' => filter_strip_tags($inputs['is_corner']),
            'corner_id' => isset($inputs['corner_id']) ? filter_strip_tags($inputs['corner_id']) : null,
            'is_facing' => filter_strip_tags($inputs['is_facing']),
            'facing_id' => isset($inputs['facing_id']) ? filter_strip_tags($inputs['facing_id']) : null,
            'type_id' => filter_strip_tags($inputs['type_id']),
            'status_id' => filter_strip_tags($inputs['status_id']),
        ];

        $unit = $this->model()->where([
            'floor_id' => $floor_id,
            'id' => $id,
        ])->update($data);

        return $unit;
    }

    public function destroy($site_id, $floor_id, $id)
    {
        $site_id = decryptParams($site_id);
        $floor_id = decryptParams($floor_id);
        $id = decryptParams($id);

        $this->model()->whereIn('id', $id)->get()->each(function ($row) {
            $row->delete();
        });

        return true;
    }
}
