<?php

namespace App\Http\Controllers;

use App\DataTables\TrialBalanceDataTable;
use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\Site;
use DateTime;
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
        $account_head_code = $request->account_head_code;


        $account_ledgers = AccountLedger::when(($start_date && $end_date), function ($query) use ($start_date,$end_date) {
            $query->whereBetween('created_at', [$start_date, $end_date]);
            return $query;
        })->where('account_head_code',$account_head_code)->get();

        if(count($account_ledgers) > 0)
        {
            
            $table =  '<thead>'.
            '<tr>'.
                '<th class="text-nowrap">#</th>'.
                '<th class="text-nowrap">Account Codes</th>'.
                '<th class="text-nowrap">Opening Balance</th>'.
                '<th class="text-nowrap">Debit</th>'.
                '<th class="text-nowrap">Credit</th>'.
                '<th class="text-nowrap">Closing Balance</th>'.
                '<th class="text-nowrap">Transactions At</th>'.
            '</tr>'.
        '</thead>'.
        '<tbody>';
             $i = 1;
             $starting_balance_index = 0;
            $starting_balance = [];
            $ending_balance = 0;
             foreach($account_ledgers as $account_ledger)
             {
                if(substr($account_ledger->account_head_code, 0, 2) == 10 || substr($account_ledger->account_head_code, 0, 2) == 12 )
                {
                   
                    $ending_balance = $account_ledger->credit - $account_ledger->debit;
                    array_push($starting_balance,$ending_balance); 
                }else {
                    $ending_balance = $account_ledger->debit - $account_ledger->credit;
                    array_push($starting_balance,$ending_balance);
                }

                if ($i > 1)
                {
                    $new_starting_balance = ($ending_balance + $starting_balance[$starting_balance_index - 1]);
                    $starting_balance[$starting_balance_index]= $new_starting_balance;

                }
               $table .='<tr>'.                      
               '<td>'.$i.'</td>'.                     
               '<td>'.account_number_format($account_ledger->account_head_code).'</td>'.                      
               '<td>'.number_format(($i > 1)?$starting_balance[$starting_balance_index - 1]:0).'</td>'.                      
               '<td>'.number_format($account_ledger->debit).'</td>'.                     
               '<td>'.number_format($account_ledger->credit).'</td>'.
               '<td>'.number_format(($i > 1)?$new_starting_balance:$ending_balance).'</td>'.                     
                                    
               '<td>'.
               '<span>'. date_format(new DateTime($account_ledger->created_at), 'h:i:s')
                .'</span>'. '<br> <span class="text-primary fw-bold">'.
                  date_format(new DateTime($account_ledger->created_at), 'Y-m-d').
                  '</span>'.
               
               '</td>'.
               '</tr>';
               $i++;
               $starting_balance_index++;
             }
             $balance_add_starting = array_pop($starting_balance);
            
        $table .= '</tbody>'.
        '<tfoot>'.
            '<tr>'.
                '<th></th>'.
                '<th></th>'.
                '<th>'.collect($starting_balance)->sum().'</th>'.
                '<th>'.number_format($account_ledgers->pluck('debit')->sum()).'</th>'.
                '<th>'.number_format($account_ledgers->pluck('credit')->sum()).'</th>'.
                '<th>'.collect($starting_balance)->sum()+$balance_add_starting.'</th>'.
                '<th></th>'.
            '</tr>'.
        '</tfoot>';
        return [
            'status' => true,
            'message' => 'Table',
            'data' => $table
        ];

        }else
        {
                        
            $table =  '<thead>'.
            '<tr>'.
                '<th class="text-nowrap">#</th>'.
                '<th class="text-nowrap">Account Codes</th>'.
                '<th class="text-nowrap">Opening Balance</th>'.
                '<th class="text-nowrap">Debit</th>'.
                '<th class="text-nowrap">Credit</th>'.
                '<th class="text-nowrap">Closing Balance</th>'.
                '<th class="text-nowrap">Transactions At</th>'.
            '</tr>'.
        '</thead>'.
        '<tbody>'.
            '<tr>'.                      
               '<td></td>'.                     
               '<td></td>'.                     
               //    '<td class="text-nowrap">'.$start_date
               //    .' and '.$end_date.' Date'.'</td>'.                     
               '<td class="text-nowrap">No Record Found Of </td>'.                                  
               '<td></td>'.                     
               '<td></td>'.                     
               '<td></td>'.                     
               '<td></td>'.                     
            '</tr>'.
               
        '</tbody>'.
        '<tfoot>'.
            '<tr>'.
                '<th></th>'.
                '<th></th>'.
                '<th>0</th>'.
                '<th>0</th>'.
                '<th>0</th>'.
                '<th>0</th>'.
                '<th></th>'.
            '</tr>'.
        '</tfoot>';

        return [
            'status' => true,
            'message' => 'no data ',
            'data' => $table
        ];
        }
    }
}
