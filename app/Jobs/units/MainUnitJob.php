<?php

namespace App\Jobs\units;

use App\Models\Floor;
use Illuminate\Bus\Batchable;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class MainUnitJob implements ShouldQueue
{
    use Batchable, Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private
        $site_id,
        $floor_id,
        $inputs,
        $isUnitActive;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($site_id, $floor_id, $inputs, $isUnitActive)
    {
        $this->site_id = decryptParams($site_id);
        $this->floor_id = decryptParams($floor_id);
        $this->inputs = $inputs;
        $this->isUnitActive = $isUnitActive;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = [];
        $jobs = [];
        $floor = (new Floor())->find($this->floor_id);

        $unitNumberDigits = $floor->site->siteConfiguration->unit_number_digits;

        for ($i = $this->inputs['slider_input_1']; $i <= $this->inputs['slider_input_2']; $i++) {
            $data[] = [
                'floor_id' => $this->floor_id,
                'name' => 'Unit ' . $i,
                'width' => $this->inputs['width'],
                'length' => $this->inputs['length'],
                'unit_number' => $i,
                'floor_unit_number' => $floor->short_label . '-' . Str::padLeft($i, $unitNumberDigits, '0'),
                'net_area' => $this->inputs['net_area'],
                'gross_area' => $this->inputs['gross_area'],
                'price_sqft' => $this->inputs['price_sqft'],
                'total_price' => $this->inputs['total_price'],
                'is_corner' => $this->inputs['is_corner'],
                'corner_id' => isset($this->inputs['corner_id']) ? $this->inputs['corner_id'] : null,
                'is_facing' => $this->inputs['is_facing'],
                'facing_id' => isset($this->inputs['facing_id']) ? $this->inputs['facing_id'] : null,
                'type_id' => $this->inputs['type_id'],
                'status_id' => $this->inputs['status_id'],
                'active' => $this->isUnitActive,
                'created_at' => now(),
                'updated_at' => now()
            ];
        }

        $jobs = array_map(function ($data) {
            return new CreateUnitJob($data);
        }, array_chunk($data, 5));

        $this->batch()->add($jobs);
    }
}
