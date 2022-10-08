<?php

namespace App\Http\Controllers;

use App\DataTables\RecoverySalesPlanDataTable;
use App\Models\SalesPlan;
use Illuminate\Http\Request;

class AccountsRecoveryController extends Controller
{
    public function dashboard(Request $request, $site_id)
    {
        return view('app.sites.accounts.recovery.dashboard');
    }

    public function calender(Request $request, $site_id)
    {
        return view('app.sites.accounts.recovery.calender');
    }

    public function salesPlan(RecoverySalesPlanDataTable $dataTable, $site_id)
    {
        $site_id = decryptParams($site_id);


        $data = [
            'site_id' => $site_id,
            'salesPlan' => (new SalesPlan())->with(['unit', 'stakeholder', 'additionalCosts', 'installments', 'leadSource', 'receipts'])->where(['status' => 1])->get(),
        ];
        // return $dataTable->with($data)->ajax();

        if (request()->ajax()) {
            return $dataTable->with($data)->ajax();
        }

        return view('app.sites.accounts.recovery.sales-plan', $data);
    }
}
