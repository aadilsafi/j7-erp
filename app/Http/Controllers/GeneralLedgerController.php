<?php

namespace App\Http\Controllers;

use App\DataTables\TrialBalanceDataTable;
use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\Site;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;

class GeneralLedgerController extends Controller
{
    //

    public function index(TrialBalanceDataTable $dataTable, $site_id)
    {
        try {
            $site = (new Site())->find(decryptParams($site_id))->with('siteConfiguration', 'statuses')->first();
            if ($site && !empty($site)) {
                $account_head = AccountHead::where('level', 5)->whereHas('accountLedgers')->orderBy('code', 'asc')->get();
                $data = [
                    'site' => $site,
                    'account_head' => $account_head,
                ];
                return $dataTable->with($data)->render('app.sites.accounts.general_ledger.index', $data);
            }
            return redirect()->route('dashboard')->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('dashboard')->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
    public function filter(Request $request, $site_id, $account_head_code_id)
    {
        // dd('ehlo to do');
        $account_ledgers = AccountLedger::where('account_head_code', decryptParams($account_head_code_id))->get();
        $account_head = AccountHead::where('code', decryptParams($account_head_code_id))->first();
        $data = [
            'site_id' => $site_id,
            'account_ledgers' => $account_ledgers,
            'account_head' => $account_head,
        ];

        return view('app.sites.accounts.general_ledger.filter_trial_blance', $data);
    }

    public function filterTrialBalance(Request $request)
    {
        $last_date = substr($request->to_date, 0, 10);
        $start_date = substr($request->to_date, 0, 10);
        $end_date =  substr($request->to_date, 14, 10);
        $account_head_code = $request->account_head_code;
        $acount_name = AccountHead::find($account_head_code)->name;
        $acount_nature = AccountHead::find($account_head_code)->account_type;
        $account_ledgers = AccountLedger::when(($start_date && $end_date), function ($query) use ($start_date, $end_date) {
            $query->whereDate('created_date', '>=', $start_date)->whereDate('created_date', '<=', $end_date);
            // $query->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date);
            return $query;
        })
            ->when(((!$end_date) && $start_date), function ($query) use ($start_date) {
                $query->whereDate('created_date', '=', $start_date);
                // $query->whereDate('created_at', '=', $start_date);
                return $query;
            })
            ->where('account_head_code', $account_head_code)->get();


            $date=date_create($last_date);
            $last_date = date_sub($date,date_interval_create_from_date_string("1 days"));
            $getOnlyDate = date_format($last_date,"Y-m-d");

            $last_Accounts_data = AccountLedger::where('account_head_code', $account_head_code)->where('created_date' , '<', $start_date)->get();
            $last_opened_balance = 0.0;
            $amount = 0.0;
            if(isset($last_Accounts_data) && count($last_Accounts_data)>0)
            {
                foreach($last_Accounts_data as $last_data){

                    if($acount_nature == 'debit')
                    {
                        $amount = (float)$last_data->debit - (float)$last_data->credit;
                        $last_opened_balance = (float)$last_opened_balance + (float)$amount;
                    }
                    else
                    {
                        $amount =  (float)$last_data->credit - (float)$last_data->debit ;
                        $last_opened_balance = (float)$last_opened_balance + (float)$amount;
                    }
                }

            }

        if (count($account_ledgers) > 0) {
            $table =  '<thead>' .
                '<tr>' .
                '<th class="text-nowrap">#</th>' .
                // '<th class="text-nowrap">Account Name</th>' .
                '<th class="text-nowrap">Opening Balance</th>' .
                '<th class="text-nowrap">Debit</th>' .
                '<th class="text-nowrap">Credit</th>' .
                '<th class="text-nowrap">Closing Balance</th>' .
                '<th class="text-nowrap">Transactions At</th>' .
                '</tr>' .
                '</thead>' .
                '<tbody>';
            $i = 1;
            $starting_balance_index = 0;
            $starting_balance = [];
            $ending_balance = 0;
            foreach ($account_ledgers as $account_ledger) {
                if ($acount_nature == 'debit') {
                    $ending_balance =  $account_ledger->debit - $account_ledger->credit;
                    array_push($starting_balance, $ending_balance);
                } else {
                    $ending_balance =  $account_ledger->credit - $account_ledger->debit ;
                    array_push($starting_balance, $ending_balance);
                }

                // For First time
                if($acount_nature == 'debit'){
                    $closingBalance = (float)$last_opened_balance  + ((float)$account_ledger->debit - (float)$account_ledger->credit);
                }else{
                    $closingBalance = (float)$last_opened_balance  + ((float)$account_ledger->credit - (float)$account_ledger->debit);
                }

                $opening_balance = 0.0;


                if ($i > 1) {

                    if($i==2){
                        $new_starting_balance = ($closingBalance + $starting_balance[$starting_balance_index - 1]);
                        $starting_balance[$starting_balance_index] = $new_starting_balance;

                        if ($acount_nature == 'debit') {
                            $ending_balance =  $account_ledgers[0]['debit'] - $account_ledgers[0]['credit'];
                            $opening_balance = $ending_balance + $last_opened_balance;
                        } else {
                            $ending_balance =  $account_ledgers[0]['credit'] - $account_ledgers[0]['debit'] ;
                            $opening_balance = $ending_balance + $last_opened_balance;
                        }
                    }
                    else{
                        $new_starting_balance = ($ending_balance + $starting_balance[$starting_balance_index - 1]);
                        $starting_balance[$starting_balance_index] = $new_starting_balance;
                        $opening_balance = $starting_balance[$starting_balance_index - 1];
                    }

                }


                $table .= '<tr>' .
                    '<td>' . $i . '</td>' .
                    // '<td>' . $acount_name . '</td>' .
                    '<td>' . number_format(($i > 1) ?  $opening_balance : $last_opened_balance) . '</td>' .
                    '<td>' . number_format($account_ledger->debit) . '</td>' .
                    '<td>' . number_format($account_ledger->credit) . '</td>' .
                    '<td>' . number_format(($i > 1) ? $new_starting_balance : $closingBalance) . '</td>' .

                    '<td>' .
                    '<span>' . date_format(new DateTime($account_ledger->created_date), 'h:i:s')
                    . '</span>' . '<br> <span class="text-primary fw-bold">' .
                    date_format(new DateTime($account_ledger->created_date), 'Y-m-d') .
                    '</span>' .

                    '</td>' .
                    '</tr>';
                $i++;
                $starting_balance_index++;
            }
            $balance_add_starting = array_pop($starting_balance);

            $table .= '</tbody>' .
                '<tfoot>' .
                '<tr>' .
                '<th></th>' .
                '<th></th>' .
                '<th></th>' .
                // '<th>' . number_format(collect($starting_balance)->sum()) . '</th>' .
                '<th>' . number_format($account_ledgers->pluck('debit')->sum()) . '</th>' .
                '<th>' . number_format($account_ledgers->pluck('credit')->sum()) . '</th>' .
                // '<th>' . number_format(collect($starting_balance)->sum() + $balance_add_starting) . '</th>' .
                '<th></th>' .
                '<th></th>' .
                '</tr>' .
                '</tfoot>';
            return [
                'status' => true,
                'message' => 'Table',
                'data' => $table
            ];
        } else {

            $table =  '<thead>' .
                '<tr>' .
                '<th class="text-nowrap">#</th>' .
                '<th class="text-nowrap">Account Codes</th>' .
                '<th class="text-nowrap">Opening Balance</th>' .
                '<th class="text-nowrap">Debit</th>' .
                '<th class="text-nowrap">Credit</th>' .
                '<th class="text-nowrap">Closing Balance</th>' .
                '<th class="text-nowrap">Transactions At</th>' .
                '</tr>' .
                '</thead>' .
                '<tbody>' .
                '<tr>' .
                '<td></td>' .
                '<td></td>' .
                '<td></td>' .
                '<td class="text-nowrap">No Record Found</td>' .
                '<td></td>' .
                '<td></td>' .
                '<td></td>' .
                '</tr>' .

                '</tbody>' .
                '<tfoot>' .
                '<tr>' .
                '<th></th>' .
                '<th></th>' .
                '<th></th>' .
                '<th></th>' .
                '<th></th>' .
                '<th></th>' .
                '<th></th>' .
                '</tr>' .
                '</tfoot>';

            return [
                'status' => true,
                'message' => 'no data ',
                'data' => $table
            ];
        }
    }

    public function filterByDate(Request $request)
    {
        $account_head_code = $request->type_name;
        $start_date = substr($request->to_date, 0, 10);
        $end_date =  substr($request->to_date, 14, 10);

        $account_head = AccountHead::when(($start_date && $end_date), function ($query) use ($start_date, $end_date) {
            $query->whereDate('created_at', '>=', $start_date)->whereDate('created_at', '<=', $end_date);
            return $query;
        })->when(($request->months_id == 'months12'), function ($query) {
            $query->whereMonth('created_at', '>=', Carbon::now()->subMonth(12));
            return $query;
        })
            ->when(($request->months_id == 'months6'), function ($query) {
                $query->whereMonth('created_at', '>=', Carbon::now()->subMonth(6));
                return $query;
            })
            ->when(($request->months_id == 'months1'), function ($query) {
                $query->whereMonth('created_at', '>=', Carbon::now()->subMonth());
                return $query;
            })
            ->when(($request->months_id == 'months3'), function ($query) {
                $query->whereMonth('created_at', '>=', Carbon::now()->subMonth(3));
                return $query;
            })
            ->when(($account_head_code > 0), function ($query) use ($account_head_code) {
                $query->where('code', $account_head_code);
                return $query;
            })
            ->where('level', 5)->whereHas('accountLedgers')->get();
        if (count($account_head) > 0) {
            $table = '<thead>' .
                '<tr>' .
                '<th title="#">#</th>' .
                '<th title="Account Codes">Account Codes</th>' .
                '<th title="Account Name">Account Name</th>' .
                '<th title="Opening Balance">Opening Balance</th>' .
                '<th title="Debit">Debit</th>' .
                '<th title="Credit">Credit</th>' .
                '<th title="Closing Balance">Closing Balance</th>' .
                '<th title="Transactions At">Transactions At</th>' .
                '<th title="Action">Action</th>' .
                '</tr>' .
                '</thead>' .
                '<tbody>';
            $i = 1;
            foreach ($account_head as $account) {


                $credits = $account->accountLedgers->pluck('credit')->sum();
                $debits = $account->accountLedgers->pluck('debit')->sum();
                $ending = 0;
                if ($account->account_type == 'debit') {
                    $ending = number_format($debits - $credits);
                } else {
                    $ending = number_format($credits - $debits);
                }

                $table .= '<tr>' .
                    '<td class="text-nowrap">' . $i . '</td>' .
                    '<td class="text-nowrap">' . account_number_format($account->code) . '</td>' .
                    '<td class="text-nowrap">' . $account->name . '</td>' .
                    '<td class="text-nowrap">' . '0' . '</td>' .
                    '<td class="text-nowrap">' . number_format($credits) . '</td>' .
                    '<td class="text-nowrap">' . number_format($debits) . '</td>' .
                    '<td class="text-nowrap">' . $ending . '</td>' .
                    '<td class="text-nowrap">' .
                    '<span>' . date_format(new DateTime($account->created_at), 'h:i:s')
                    . '</span>' . '<br> <span class="text-primary fw-bold">' .
                    date_format(new DateTime($account->created_at), 'Y-m-d') .
                    '</span>' .

                    '</td>' .
                    '<td>' . view('app.sites.accounts.general_ledger.action', ['site_id' => ($request->site_id), 'account_head_code' => $account->code]) . '</td>' .
                    '</tr>';
                $i++;
            }

            return [
                'status' => true,
                'message' => ' filter by date mouth or year',
                'data' => $table
            ];
        } else {

            $table =  '<thead>' .
                '<tr>' .
                '<th class="text-nowrap">#</th>' .
                '<th class="text-nowrap">Account Codes</th>' .
                '<th class="text-nowrap">Opening Balance</th>' .
                '<th class="text-nowrap">Debit</th>' .
                '<th class="text-nowrap">Credit</th>' .
                '<th class="text-nowrap">Closing Balance</th>' .
                '<th class="text-nowrap">Transactions At</th>' .
                '</tr>' .
                '</thead>' .
                '<tbody>' .
                '<tr>' .
                '<td></td>' .
                '<td></td>' .
                '<td > </td>' .
                '<td class="text-nowrap" colspan="2">No data available in table</td>' .
                '<td></td>' .
                '<td></td>' .
                '</tr>' .

                '</tbody>';

            return [
                'status' => true,
                'message' => 'no data ',
                'data' => $table
            ];
        }
    }
}
