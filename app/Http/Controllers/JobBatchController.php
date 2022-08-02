<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class JobBatchController extends Controller
{
    function getJobBatchByID(string $batchId)
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
}
