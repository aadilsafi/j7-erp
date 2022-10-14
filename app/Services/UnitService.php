<?php

namespace App\Services;

use App\Jobs\units\MainUnitJob;
use App\Models\{
    Floor,
    Unit,
    UserBatch,
};
use App\Notifications\DefaultNotification;
use App\Services\Interfaces\UnitInterface;
use App\Utils\Enums\UserBatchActionsEnum;
use App\Utils\Enums\UserBatchStatusEnum;
use Illuminate\Bus\Batch;
use Illuminate\Support\Str;
use Spatie\Activitylog\Facades\CauserResolver;
use Illuminate\Support\Facades\{Notification, Bus};
use Illuminate\Support\Facades\DB;


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

    public function getById($site_id, $floor_id, $id, $relationships = [])
    {
        $site_id = decryptParams($site_id);
        $floor_id = decryptParams($floor_id);
        $id = decryptParams($id);

        return $this->model()->with($relationships)->where([
            'floor_id' => $floor_id,
            'id' => $id,
        ])->first();
    }

    // Store
    public function store($site_id, $floor_id, $inputs, $isUnitActive = true)
    {
        $site_id = decryptParams($site_id);
        $floor = (new Floor())->find($floor_id);

        $totalPrice = floatval($inputs['gross_area']) * floatval($inputs['price_sqft']);

        $unitNumberDigits = (new Floor())->find($floor_id)->site->siteConfiguration->unit_number_digits;

        $data = [
            'floor_id' => $floor_id,
            'name' => filter_strip_tags($inputs['name'] ?? ''),
            'width' => filter_strip_tags($inputs['width']),
            'length' => filter_strip_tags($inputs['length']),
            'unit_number' => filter_strip_tags($inputs['unit_number']),
            'floor_unit_number' => $floor->short_label . '-' . Str::padLeft($inputs['unit_number'], $unitNumberDigits, '0'),
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

    // Store Fab Unit
    public function storeFabUnit($site_id, $floor_id, $inputs, $isUnitActive = true)
    {
        DB::transaction(function () use ($site_id, $floor_id, $inputs) {

        $site_id = decryptParams($site_id);
        $floor = (new Floor())->find($floor_id);
        $unit = (new Unit())->find($inputs['unit_id']);
        $unit_number = filter_strip_tags($inputs['unit_number']);
        $unitNumberDigits = (new Floor())->find($floor_id)->site->siteConfiguration->unit_number_digits;

        foreach ($inputs['fab-units'] as $input) {
            $totalPrice = floatval($input['gross_area']) * floatval($input['price_sqft']);

            $data = [
                'parent_id' => $unit->id,
                'floor_id' => $floor_id,
                'name' => filter_strip_tags($input['name'] ?? ''),
                'width' => filter_strip_tags($input['width']),
                'length' => filter_strip_tags($input['length']),
                'unit_number' => $unit_number,
                'floor_unit_number' => $unit->floor_unit_number . '-FAB-' . Str::padLeft($unit_number, $unitNumberDigits, '0'),
                'net_area' => filter_strip_tags($input['net_area']),
                'gross_area' => filter_strip_tags($input['gross_area']),
                'price_sqft' => filter_strip_tags($input['price_sqft']),
                'total_price' => $totalPrice,
                'is_corner' =>  false ,
                'corner_id' => null,
                'is_facing' => false,
                'facing_id' => null,
                'status_id' => 1,
                'type_id' => $unit->type_id,
                'active' => true,
            ];

            // dd($data);

            $floor = $this->model()->create($data);
            $unit_number++;
        }


        return $floor;
    });
    }


    public function storeInBulk($site_id, $floor_id, $inputs, $isUnitActive = false)
    {

        $user = auth()->user();
        $inputs['total_price'] = floatval($inputs['gross_area']) * floatval($inputs['price_sqft']);

        CauserResolver::setCauser($user);

        $batch = Bus::batch([
            new MainUnitJob($site_id, $floor_id, $inputs, $isUnitActive),
        ])->finally(function (Batch $batch) use ($site_id, $floor_id, $user) {
            $site_id = decryptParams($site_id);
            $floor_id = decryptParams($floor_id);

            $data = [
                'title' => 'Job Done!',
                'message' => 'Unit Construction Completed',
                'description' => 'Unit Construction Completed',
                'url' => route('sites.floors.units.index', ['site_id' => encryptParams($site_id), 'floor_id' => encryptParams($floor_id)]),
            ];
            Notification::send($user, new DefaultNotification($data));
        })->dispatch();

        (new UserBatch())->create([
            'site_id' => decryptParams($site_id),
            'user_id' => auth()->user()->id,
            'job_batch_id' => $batch->id,
            'actions' => UserBatchActionsEnum::COPY_UNITS,
            'batch_status' => UserBatchStatusEnum::PENDING,
        ]);

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
