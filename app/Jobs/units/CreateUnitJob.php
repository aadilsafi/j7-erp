<?php

namespace App\Jobs\units;

use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CreateUnitJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $site_id, $floor_id, $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($site_id, $floor_id, $data)
    {
        $this->site_id = decryptParams($site_id);
        $this->floor_id = decryptParams($floor_id);
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('CreateUnitJob: ' . $this->site_id . ' ' . $this->floor_id . ' ' . json_encode($this->data));
        sleep(1);
    }
}
