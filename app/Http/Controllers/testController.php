<?php

namespace App\Http\Controllers;

use App\Jobs\testJob as JobsTestJob;
use App\Models\Floor;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\DB;
use NumberFormatter;
use Spatie\Activitylog\Models\Activity;

class testController extends Controller
{

    private $financialTransactionInterface;

    public function __construct(FinancialTransactionInterface $financialTransactionInterface)
    {
        $this->financialTransactionInterface = $financialTransactionInterface;
    }

    public function jobs(Request $request)
    {

        // return (new Floor())->with(['site', 'site.siteConfiguration'])->find(1)->site->siteConfiguration;
        for ($i = 0; $i < 2000; $i++) {
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

    function test()
    {
        print "<pre>";
        echo (new NumberFormatter("en", NumberFormatter::PATTERN_DECIMAL))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::DECIMAL))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::CURRENCY))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::PERCENT))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::SCIENTIFIC))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::SPELLOUT))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::ORDINAL))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::DURATION))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::CURRENCY_ACCOUNTING))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::DEFAULT_STYLE))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::IGNORE))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::TYPE_DEFAULT))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::TYPE_INT32))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::TYPE_INT64))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::TYPE_DOUBLE))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::TYPE_CURRENCY))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::PARSE_INT_ONLY))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::GROUPING_USED))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::DECIMAL_ALWAYS_SHOWN))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::MAX_INTEGER_DIGITS))->format(190);
        echo (new NumberFormatter("en", NumberFormatter::MIN_INTEGER_DIGITS))->format(190);
        print "</pre>";
    }

    function activityLog(Request $request)
    {
        return $lastLoggedActivity = Activity::all()->last();
        return $lastLoggedActivity->changes();
        $UserBrowserInfo = getUserBrowserInfo($request);

        return activity()
            ->causedBy(auth()->user())
            ->inLog('asdad')
            ->withProperties(json_encode($UserBrowserInfo))
            ->event('verified')
            ->log('edited');


        $lastLoggedActivity->subject; //returns an instance of an eloquent model
        $lastLoggedActivity->causer; //returns an instance of your user model
        $lastLoggedActivity->getExtraProperty('customProperty'); //returns 'customValue'
        return $lastLoggedActivity->description; //returns 'Look, I logged something'
    }

    function createAccount(){
        return $this->financialTransactionInterface->makeReceiptTransaction(6);
    }
}
