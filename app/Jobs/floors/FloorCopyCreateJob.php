<?php

namespace App\Jobs\floors;

use App\Jobs\units\CreateUnitJob;
use App\Models\Floor;
use App\Models\Unit;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FloorCopyCreateJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data, $prevFloorId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($prevFloorId, $data)
    {
        $this->data = $data;
        $this->prevFloorId = $prevFloorId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // sleep(2);
        $newFloor = (new Floor())->create($this->data);

        $floorUnits = (new Unit())->where([
            'floor_id' => $this->prevFloorId,
            'active' => true,
            'has_sub_units' => false,
        ])->get();

        $prevFloor = (new Floor())->with(['site', 'site.siteConfiguration'])->find($this->prevFloorId);

        $floorUnits = collect($floorUnits)->map(function ($unit, $key) use ($newFloor, $prevFloor) {
            unset($unit->id);
            $unit->floor_id = $newFloor->id;
            $unit->name = 'Unit ' . $unit->unit_number;
            $unit->floor_unit_number = strtoupper($newFloor->short_label) . '-' . Str::padLeft($unit->unit_number, $prevFloor->site->siteConfiguration->unit_number_digits, '0');
            $unit->status_id = 1;
            $unit->active = false;
            $unit->has_sub_units = false;
            $unit->is_for_rebate = false;
            $unit->created_at = now();
            $unit->updated_at = now();

            return $unit;
        });

        foreach ($floorUnits->toArray() as $key => $units) {
            $this->batch()->add(new CreateUnitJob($units));
        }
    }
}
