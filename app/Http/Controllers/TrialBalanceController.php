<?php

namespace App\Http\Controllers;

use App\DataTables\TrialBalanceDataTable;
use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\Site;
use Exception;
use Illuminate\Http\Request;

class TrialBalanceController extends Controller
{
    //

    public function index(TrialBalanceDataTable $dataTable, $site_id)
    {
        try {
            $site = (new Site())->find(decryptParams($site_id))->with('siteConfiguration', 'statuses')->first();
            if ($site && !empty($site)) {
                $data = [
                    'site' => $site,
                ];
                return $dataTable->with($data)->render('app.sites.accounts.trial_balance.index', $data);
            }
            return redirect()->route('dashboard')->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('dashboard')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
    public function filter(Request $request, $site_id, $account_head_code_id)
    {
        $account_ledgers = AccountLedger::where('account_head_code', decryptParams($account_head_code_id))->get();
        $account_head = AccountHead::where('code', decryptParams($account_head_code_id))->first();
        $data = [
            'site_id' => $site_id,
            'account_ledgers' => $account_ledgers,
            'account_head' => $account_head,
        ];

        return view('app.sites.accounts.trial_balance.filter_trial_blance', $data);
    }

    public function filterTrialBalance(Request $request)
    {
        $start_date = substr($request->to_date, 0, 10);
        $end_date =  substr($request->to_date, 14, 10);

        
    }
}
