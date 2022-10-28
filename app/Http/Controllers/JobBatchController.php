<?php

namespace App\Http\Controllers;

use App\Models\UserBatch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;

class JobBatchController extends Controller
{
    function getJobBatchByID(string $batch_id)
    {
        $batch = Bus::findBatch($batch_id);
        if (!is_null($batch)) {
            // if ($batch->finished()) {
            //     DB::table('job_batches')->where('id', $batch->id)->delete();
            // } else
            return apiSuccessResponse($batch->toArray());
        }
        return apiErrorResponse('Batch not found');
    }

    function clearAllQueues()
    {
        (new UserBatch())->where('user_id', auth()->user()->id)->delete();

        return redirect()->back()->withSuccess('All Cleared.');
    }
}
