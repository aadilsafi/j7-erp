<?php

namespace App\Services;

use App\Models\Unit;
use App\Services\Interfaces\UnitInterface;
use Illuminate\Support\Str;

class UnitService implements UnitInterface
{

    public function model()
    {
        return new Unit();
    }

    // Get
    public function getByAll($site_id)
    {
        $site_id = decryptParams($site_id);

        return $this->model()->where('site_id', $site_id)->get();
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

    // Store
    public function store($site_id, $inputs)
    {
        $data = [
            'site_id' => decryptParams($site_id),
            'name' => filter_strip_tags($inputs['name']),
            'width' => filter_strip_tags($inputs['width']),
            'length' => filter_strip_tags($inputs['length']),
            'order' => filter_strip_tags($inputs['floor_order']),
        ];

        $floor = $this->model()->create($data);
        return $floor;
    }

    public function update($site_id, $id, $inputs)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        $data = [
            'site_id' => $site_id,
            'name' => filter_strip_tags($inputs['name']),
            'width' => filter_strip_tags($inputs['width']),
            'length' => filter_strip_tags($inputs['length']),
            'order' => filter_strip_tags($inputs['floor_order']),
        ];

        $floor = $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ])->update($data);

        return $floor;
    }

    public function destroy($site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ])->delete();

        return true;
    }
}
