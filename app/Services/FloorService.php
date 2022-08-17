<?php

namespace App\Services;

use App\Jobs\floors\FloorCopyMainJob;
use App\Models\Floor;
use App\Services\Interfaces\FloorInterface;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Str;

class FloorService implements FloorInterface
{

    public function model()
    {
        return new Floor();
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
            'short_label' => Str::of(filter_strip_tags($inputs['short_label']))->upper(),
            'floor_area' => filter_strip_tags($inputs['floor_area']),
            'order' => filter_strip_tags($inputs['floor_order']),
            'active' => true,
        ];

        $floor = $this->model()->create($data);
        return $floor;
    }

    public function storeInBulk($site_id, $user_id, $inputs, $isFloorActive = false)
    {
        FloorCopyMainJob::dispatch($site_id, $user_id, $inputs, $isFloorActive);
        return true;
    }

    public function update($site_id, $id, $inputs)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        $data = [
            'site_id' => $site_id,
            'name' => filter_strip_tags($inputs['name']),
            'short_label' => Str::of(filter_strip_tags($inputs['short_label']))->upper(),
            'floor_area' => filter_strip_tags($inputs['floor_area']),
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
