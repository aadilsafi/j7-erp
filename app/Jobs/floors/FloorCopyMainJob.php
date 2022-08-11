<?php

namespace App\Jobs\floors;

use App\Models\Floor;
use App\Models\UserBatch;
use App\Utils\Enums\{
    UserBatchStatusEnum,
    UserBatchActionsEnum
};
use Illuminate\Bus\{Batchable, Queueable};
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Log;

class FloorCopyMainJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private
        $site_id,
        $inputs,
        $user_id,
        $isFloorActive;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($site_id, $user_id, $inputs, $isFloorActive)
    {
        $this->site_id = decryptParams($site_id);
        $this->user_id = decryptParams($user_id);
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

        $jobs = [];

        for ($i = $this->inputs['copy_floor_from']; $i <= $this->inputs['copy_floor_to']; $i++) {
            $data = [
                'site_id' => $this->site_id,
                'name' => 'Floor ' . $i,
                'width' => $floor->width,
                'length' => $floor->length,
                'order' => $i,
                'active' => $this->isFloorActive,
            ];

            // sleep(2);
            $batch = Bus::batch([
                new FloorCopyCreateJob($floor->id, $data),
            ])->dispatch();

            (new UserBatch())->create([
                'site_id' => $this->site_id,
                'user_id' => $this->user_id,
                'job_batch_id' => $batch->id,
                'actions' => UserBatchActionsEnum::COPY_FLOORS,
                'batch_status' => UserBatchStatusEnum::PENDING,
            ]);
        }
    }
}
