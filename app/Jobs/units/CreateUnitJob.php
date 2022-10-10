<?php

namespace App\Jobs\units;

use App\Models\Unit;
use Exception;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CreateUnitJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::transaction(function () {
            sleep(5);
            $prevRecord = (new Unit)->where('floor_id', $this->data['floor_id'])->where('unit_number', $this->data['unit_number'])->first();
            if ($prevRecord) {
                $exception = new Exception('Unit already exists');
                $this->fail($exception);
            }
            (new Unit())->create($this->data);
        });
    }
}
