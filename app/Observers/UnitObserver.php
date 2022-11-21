<?php

namespace App\Observers;

use App\Models\Unit;
use Illuminate\Support\Facades\DB;

class UnitObserver
{
    /**
     * Handle the Unit "created" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function created(Unit $unit)
    {
        //
    }

    /**
     * Handle the Unit "updated" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function updated(Unit $unit)
    {
        DB::transaction(function () use ($unit) {
            $floor = $unit->floor;
            $floor_plan = $floor->floor_plan;
            if ($floor_plan) {
                $floor_plan = json_decode($floor_plan);
                $features = &$floor_plan->features;

                foreach ($features as &$feature ) {
                    if($feature->properties->unit_no == $unit->floor_unit_number){
                        $floorplan_unit = &$feature->properties;

                        $floorplan_unit->type = $unit->type->name;
                        $floorplan_unit->status = $unit->status->name;
                        $floorplan_unit->net_area = $unit->net_area;
                        $floorplan_unit->gross_area = $unit->gross_area;

                        break; 
                    }
                }

                $floor->floor_plan = json_encode($floor_plan);
                $floor->save();
            }
        });
    }

    /**
     * Handle the Unit "deleted" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function deleted(Unit $unit)
    {
        //
    }

    /**
     * Handle the Unit "restored" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function restored(Unit $unit)
    {
        //
    }

    /**
     * Handle the Unit "force deleted" event.
     *
     * @param  \App\Models\Unit  $unit
     * @return void
     */
    public function forceDeleted(Unit $unit)
    {
        //
    }
}
