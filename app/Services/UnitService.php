<?php

namespace App\Services;

use App\Jobs\units\MainUnitJob;
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

        $data = [
            'floor_id' => $floor_id,
            'name' => filter_strip_tags($inputs['name']),
            'width' => filter_strip_tags($inputs['width']),
            'length' => filter_strip_tags($inputs['length']),
            'unit_number' => filter_strip_tags($inputs['unit_number']),
            'price' => filter_strip_tags($inputs['price']),
            'is_corner' => filter_strip_tags($inputs['is_corner']),
            'corner_id' => isset($inputs['corner_id']) ? filter_strip_tags($inputs['corner_id']) : null,
            'is_facing' => filter_strip_tags($inputs['is_facing']),
            'facing_id' => isset($inputs['facing_id']) ? filter_strip_tags($inputs['facing_id']) : null,
            'type_id' => filter_strip_tags($inputs['type_id']),
            'status_id' => filter_strip_tags($inputs['status_id']),
            'active' => $isUnitActive,
        ];

        $floor = $this->model()->create($data);
        return $floor;
    }

    public function storeInBulk($site_id, $floor_id, $inputs, $isUnitActive = true)
    {

        // dd($inputs, 'bulk');
        $batch = Bus::batch([
            new MainUnitJob($site_id, $floor_id, $inputs, false),
        ])->dispatch();

        return $batch;
    }

    public function update($site_id, $floor_id, $id, $inputs)
    {
        $site_id = decryptParams($site_id);
        $floor_id = decryptParams($floor_id);
        $id = decryptParams($id);

        $data = [
            'name' => filter_strip_tags($inputs['name']),
            'width' => filter_strip_tags($inputs['width']),
            'length' => filter_strip_tags($inputs['length']),
            'price' => filter_strip_tags($inputs['price']),
            'is_corner' => filter_strip_tags($inputs['is_corner']),
            'corner_id' => filter_strip_tags($inputs['corner_id']),
            'is_facing' => filter_strip_tags($inputs['is_facing']),
            'facing_id' => filter_strip_tags($inputs['facing_id']),
            'type_id' => filter_strip_tags($inputs['type_id']),
            'status_id' => filter_strip_tags($inputs['status_id']),
        ];

        // dd($data);

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

        $this->model()->where('floor_id', $floor_id)->whereIn('id', $id)->delete();

        return true;
    }
}
