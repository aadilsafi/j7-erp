<?php

namespace App\Http\Controllers;

use App\DataTables\ChartOfAccountsDataTable;
use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\Site;
use Exception;
use Illuminate\Http\Request;

class ChartsOfAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index( $site_id)
    {
        $account_of_heads = AccountHead::get();
        $accountLedgers = AccountHead::whereHas('accountLedgers')->get();
        $accountLedgers_all = AccountLedger::all();
        $account_of_heads_codes = $account_of_heads->where('level',1)->pluck('code')->toArray();
        $account_balances = [];
        foreach($account_of_heads_codes as $account_of_heads_code)
        {
            foreach($accountLedgers_all as $accountLedger)
            {
                if(substr($accountLedger->account_head_code, 0, 2) == $account_of_heads_code)
                {
                    array_push($account_balances,
                        ['credit'.'_'.$account_of_heads_code=>$accountLedger->credit,'debit'.'_'.$account_of_heads_code=>$accountLedger->debit],
                    );
                }
            }
        }
        // dd(collect($account_balances)->pluck('debit','debit_10'));
        try {
            $site = (new Site())->find(decryptParams($site_id))->with('siteConfiguration', 'statuses')->first();
            if ($site && !empty($site)) {
                $data = [
                    'site' => $site,
                    'account_of_heads' => $account_of_heads,
                    'account_of_heads_codes' => $account_of_heads_codes,
                    'account_balances' => $account_balances,
                    'accountLedgers_all' => $accountLedgers_all
                ];
                return view('app.sites.accounts.chart_of_accounts.index', $data);
            }
            return redirect()->route('dashboard')->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('dashboard')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
