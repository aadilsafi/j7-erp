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
        ])->get();

        $unitNumberDigits = (new Floor())->find($this->prevFloorId)->site->siteConfiguration->unit_number_digits;

        $floorUnits = collect($floorUnits)->map(function ($unit, $key) use ($newFloor, $unitNumberDigits) {

            unset($unit->id);
            $unit->floor_id = $newFloor->id;
            $unit->floor_unit_number = $unit->floor_id . Str::padLeft($unit->unit_number, $unitNumberDigits, '0');
            $unit->status_id = 1;
            $unit->active = false;
            $unit->created_at = now();
            $unit->updated_at = now();

            return $unit;
        })->chunk(10);

        $jobs = [];
        foreach ($floorUnits->toArray() as $key => $units) {
            $jobs[] = new CreateUnitJob($units);
        }

        $this->batch()->add($jobs);
    }
}
