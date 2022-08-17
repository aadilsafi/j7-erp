<?php

namespace App\Http\Controllers;

use App\Jobs\testJob as JobsTestJob;
use App\Models\Floor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class testController extends Controller
{
    public function jobs(Request $request)
    {
        // return (new Floor())->with(['site', 'site.siteConfiguration'])->find(1)->site->siteConfiguration;
        for($i = 0; $i < 1000; $i++) {
            JobsTestJob::dispatch();
        }
        // $batch = Bus::batch([
        //     new MainUnitJob(),
        // ])->dispatch();

        // return [
        //     'batch' => $batch,
        // ];

        return 'done';
    }

    function getBatchByID(string $batchId)
    {
        $batch = Bus::findBatch($batchId);
        if (!is_null($batch)) {
            if ($batch->finished()) {
                DB::table('job_batches')->where('id', $batch->id)->delete();
            } else
                return $batch;
        }
        return [
            'batch' => 'data not found!',
        ];
    }

    function setBatchIDInSession(string $batchId)
    {
        session('batchId', $batchId);
    }

    function unsetBatchIDInSession(string $batchId)
    {
        session()->forget(['name', 'status']);;
    }
}
