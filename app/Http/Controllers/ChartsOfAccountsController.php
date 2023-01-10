<?php

namespace App\Http\Controllers;

use App\DataTables\ChartOfAccountsDataTable;
use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\Site;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Notifications\Action;

class ChartsOfAccountsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($site_id)
    {
        $account_of_heads = AccountHead::get();
        $accountLedgers = AccountHead::whereHas('accountLedgers')->get();
        $accountLedgers_all = AccountLedger::all();
        $account_of_heads_codes = $account_of_heads->where('level', 1)->pluck('code')->toArray();
        $account_balances = [];
        foreach ($account_of_heads_codes as $account_of_heads_code) {
            foreach ($accountLedgers_all as $accountLedger) {
                if (substr($accountLedger->account_head_code, 0, 2) == $account_of_heads_code) {
                    array_push(
                        $account_balances,
                        ['credit' . '_' . $account_of_heads_code => $accountLedger->credit, 'debit' . '_' . $account_of_heads_code => $accountLedger->debit],
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
                    'accountLedgers_all' => $accountLedgers_all,
                    'first_level' => AccountHead::where('level', 1)->get(),
                    'second_level' => AccountHead::where('level', 2)->get(),
                    'third_level' => AccountHead::where('level', 3)->get(),
                    'fourth_level' => AccountHead::where('level', 4)->get(),
                    'fifth_level' => AccountHead::where('level', 5)->get(),
                ];
                // dd($data);
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

    public function getFourthLevelAccounts(Request $request, $site_id)
    {

        $starting_code = $request->code . '0000';
        $ending_code = (int)$request->code + 1;
        $ending_code = (string)$ending_code . '0000';
        $fourth_level_accounts = AccountHead::where('level', 4)->whereBetween('code', [$starting_code, $ending_code])->get();
        $balance = 0.0;
        foreach ($fourth_level_accounts  as $key => $fourth_level_account) {
            $fourth_level_accounts[$key]['formated_code'] = account_number_format($fourth_level_account->code);
            $debit = 0.0;
            $credit = 0.0;

            $ledger = AccountLedger::where('account_head_code', $fourth_level_account->code)->first();
            if (isset($ledger->debit)) {
                $debit = $ledger->debit;
            }

            if (isset($ledger->credit)) {
                $credit = $ledger->credit;
            }

            if (isset($ledger->account_type) && $ledger->account_type == 'debit') {
                $balance = $balance + ((float)$debit - (float)$credit);
            } else {
                if (isset($ledger->account_type)) {
                    $balance = $balance + ((float)$credit - (float)$debit);
                }
            }
        }
        return response()->json([
            'success' => true,
            'site_id' => $site_id,
            'starting_code' => $starting_code,
            'ending_code' => $ending_code,
            'fourth_level_accounts' => $fourth_level_accounts,
            'balance' => $balance,
        ], 200);
    }

    public function getFifthLevelAccounts(Request $request, $site_id)
    {
        $starting_code = $request->code . '0000';
        $ending_code = (int)$request->code + 1;
        $ending_code = (string)$ending_code . '0000';
        $fourth_level_account = AccountHead::where('code', $request->code)->first();
        $fourth_level_account->formated_code = account_number_format($fourth_level_account->code);
        $fourth_level_account->account_type = ucfirst($fourth_level_account->account_type);
        $fifth_level_accounts = AccountHead::where('level', 5)->whereBetween('code', [$starting_code, $ending_code])->get();

        $fourth_level_balance = 0.0;


        foreach ($fifth_level_accounts  as $key => $fifth_level_account) {
            $fifth_level_accounts[$key]['formated_code'] = account_number_format($fifth_level_account->code);
            $fifth_level_accounts[$key]['account_type'] = ucfirst($fifth_level_account->account_type);
            if ($fifth_level_account->account_type == 'debit') {
                $debit = 0.0;
                $credit = 0.0;
                $ledger = AccountLedger::where('account_head_code', $fifth_level_account->code)->first();
                if (isset($ledger->debit)) {
                    $debit = $ledger->debit;
                }
                if (isset($ledger->credit)) {
                    $credit = $ledger->credit;
                }
                $fourth_level_balance = (float)$fourth_level_balance + ((float)$debit - (float)$credit);

                $fifth_level_accounts[$key]['balance'] = (float)$debit - (float)$credit;
            } else {
                $debit = 0.0;
                $credit = 0.0;
                $ledger = AccountLedger::where('account_head_code', $fifth_level_account->code)->first();
                if (isset($ledger->debit)) {
                    $debit = $ledger->debit;
                }
                if (isset($ledger->credit)) {
                    $credit = $ledger->credit;
                }
                $fourth_level_balance = (float)$fourth_level_balance + ((float)$credit - (float)$debit);

                $fifth_level_accounts[$key]['balance'] = (float)$credit - (float)$debit;
            }
        }
        return response()->json([
            'success' => true,
            'site_id' => $site_id,
            'starting_code' => $starting_code,
            'ending_code' => $ending_code,
            'fourth_level_account' => $fourth_level_account,
            'fifth_level_accounts' => $fifth_level_accounts,
            'fourth_level_balance' => number_format($fourth_level_balance, 2),
        ], 200);
    }

    public function getFirstLevelBalance(Request $request, $site_id)
    {
        $starting_code = $request->code;
        $ending_code = (int)$request->code + 1;
        $ending_code = $ending_code . '000000000000';
        $nature = AccountHead::whereBetween('code', [$starting_code, $ending_code])->first()->account_type;

        $allAccounts =  AccountHead::whereBetween('code', [$starting_code, $ending_code])->get();

        $balance = 0.0;

        if ($nature == 'debit') {
            foreach ($allAccounts as $account) {
                $debit = 0.0;
                $credit = 0.0;
                $ledger = AccountLedger::where('account_head_code', $account->code)->first();
                if (isset($ledger->debit)) {
                    $debit = $ledger->debit;
                }
                if (isset($ledger->credit)) {
                    $credit = $ledger->credit;
                }
                $balance = $balance + ((float)$debit - (float)$credit);
            }
        } else {
            foreach ($allAccounts as $account) {
                $debit = 0.0;
                $credit = 0.0;
                $ledger = AccountLedger::where('account_head_code', $account->code)->first();
                if (isset($ledger->debit)) {
                    $debit = $ledger->debit;
                }
                if (isset($ledger->credit)) {
                    $credit = $ledger->credit;
                }
                $balance = $balance + ((float)$credit - (float)$debit);
            }
        }

        return response()->json([
            'success' => true,
            'site_id' => $site_id,
            'balance' => number_format($balance, 2),
        ], 200);
    }

    public function getSecondLevelBalance(Request $request, $site_id)
    {
        $starting_code = $request->code;
        $ending_code = (int)$request->code + 1;
        $ending_code = $ending_code . '0000000000';
        $nature = AccountHead::whereBetween('code', [$starting_code, $ending_code])->first()->account_type;

        $allAccounts =  AccountHead::whereBetween('code', [$starting_code, $ending_code])->get();

        $balance = 0.0;

        if ($nature == 'debit') {
            foreach ($allAccounts as $account) {
                $debit = 0.0;
                $credit = 0.0;
                $ledger = AccountLedger::where('account_head_code', $account->code)->first();
                if (isset($ledger->debit)) {
                    $debit = $ledger->debit;
                }
                if (isset($ledger->credit)) {
                    $credit = $ledger->credit;
                }
                $balance = $balance + ((float)$debit - (float)$credit);
            }
        } else {
            foreach ($allAccounts as $account) {
                $debit = 0.0;
                $credit = 0.0;
                $ledger = AccountLedger::where('account_head_code', $account->code)->first();
                if (isset($ledger->debit)) {
                    $debit = $ledger->debit;
                }
                if (isset($ledger->credit)) {
                    $credit = $ledger->credit;
                }
                $balance = $balance + ((float)$credit - (float)$debit);
            }
        }

        return response()->json([
            'success' => true,
            'site_id' => $site_id,
            'balance' => number_format($balance, 2),
        ], 200);
    }

    public function getThirdLevelBalance(Request $request, $site_id)
    {
        $starting_code = $request->code;
        $ending_code = (int)$request->code + 1;
        $ending_code = $ending_code . '00000000';
        $nature = AccountHead::whereBetween('code', [$starting_code, $ending_code])->first()->account_type;

        $allAccounts =  AccountHead::whereBetween('code', [$starting_code, $ending_code])->get();

        $balance = 0.0;

        if ($nature == 'debit') {
            foreach ($allAccounts as $account) {
                $debit = 0.0;
                $credit = 0.0;
                $ledger = AccountLedger::where('account_head_code', $account->code)->first();
                if (isset($ledger->debit)) {
                    $debit = $ledger->debit;
                }
                if (isset($ledger->credit)) {
                    $credit = $ledger->credit;
                }
                $balance = $balance + ((float)$debit - (float)$credit);
            }
        } else {
            foreach ($allAccounts as $account) {
                $debit = 0.0;
                $credit = 0.0;
                $ledger = AccountLedger::where('account_head_code', $account->code)->first();
                if (isset($ledger->debit)) {
                    $debit = $ledger->debit;
                }
                if (isset($ledger->credit)) {
                    $credit = $ledger->credit;
                }
                $balance = $balance + ((float)$credit - (float)$debit);
            }
        }

        return response()->json([
            'success' => true,
            'site_id' => $site_id,
            'balance' => number_format($balance, 2),
        ], 200);
    }

}
