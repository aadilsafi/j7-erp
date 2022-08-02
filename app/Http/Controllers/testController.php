<?php

namespace App\Http\Controllers;

use App\Jobs\units\MainUnitJob;
use App\Jobs\units\TestJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class testController extends Controller
{
    public function jobs(Request $request)
    {
        // $batch = Bus::batch([
        //     new MainUnitJob(),
        // ])->dispatch();

        // return [
        //     'batch' => $batch,
        // ];
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
