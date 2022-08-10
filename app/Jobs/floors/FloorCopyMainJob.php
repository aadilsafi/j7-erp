<?php

namespace App\Jobs\floors;

use App\Models\Floor;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class FloorCopyMainJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private
        $site_id,
        $inputs,
        $isFloorActive;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($site_id, $inputs, $isFloorActive)
    {
        $this->site_id = decryptParams($site_id);
        $this->inputs = $inputs;
        $this->isFloorActive = $isFloorActive;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $floor = (new Floor())->where([
            'id' => $this->inputs['floor'],
            'site_id' => $this->site_id,
            'active' => true,
        ])->first();

        for ($i = $this->inputs['copy_floor_from']; $i <= $this->inputs['copy_floor_to']; $i++) {
            $data = [
                'site_id' => $this->site_id,
                'name' => 'Floor ' . $i,
                'width' => $floor->width,
                'length' => $floor->length,
                'order' => $i,
                'active' => $this->isFloorActive,
            ];

            $floor = (new Floor())->create($data);

            Log::info(json_encode($floor));
        }






        // $jobs = array_map(function ($data) {
        //     return new FloorCopyCreateJob($data);
        // }, $data);



        // $this->batch()->add($jobs);








        // $newFloor = (new Floor())->create([
        //     'site_id' => $this->site_id,
        //     'name' => $this->inputs->copy_floor_from,
        //     'width' => $floor->width,
        //     'length' => $floor->length,
        //     'order' => $this->inputs->order,
        //     'active' => $this->isFloorActive,
        // ]);

        // // {
        // //     "_token": "NLgXtMWQeZR6VqYQpXFbDKiRcRrAWiMahkA13Ysq",
        // //     "floor": "1",
        // //     "copy_floor_from": "4",
        // //     "copy_floor_to": "50"
        // //     }
        // //
    }
}
