<?php

namespace App\Http\Controllers;

use App\DataTables\RecoverySalesPlanDataTable;
use App\Models\SalesPlan;
use App\Services\AccountRecevories\AccountRecevoryInterface;
use DataTables;
use Illuminate\Http\Request;

class AccountsRecoveryController extends Controller
{

    private $accountRecevoryInterface;

    public function __construct(
        AccountRecevoryInterface $accountRecevoryInterface
    ) {
        $this->accountRecevoryInterface = $accountRecevoryInterface;
    }

    public function dashboard(Request $request, $site_id)
    {
        return view('app.sites.accounts.recovery.dashboard');
    }

    public function calender(Request $request, $site_id)
    {
        return view('app.sites.accounts.recovery.calender');
    }

    public function salesPlan($site_id)
    {
        $site_id = decryptParams($site_id);

        $salesPlans = (new SalesPlan())->with(['installments'])->where(['status' => 1])->get();

        $maxInstallments = collect($salesPlans)->transform(function ($salesPlan) {
            return $salesPlan->installments->where('type', 'installment')->count();
        })->max();

        if (request()->ajax()) {
            $dataTable = $this->accountRecevoryInterface->generateDataTable($site_id);
            return DataTables::of($dataTable)->make(true);
        }

        return view('app.sites.accounts.recovery.sales-plan', ['site_id' => $site_id, 'salesPlans' => $salesPlans, 'max_installments' => $maxInstallments]);
    }
}
