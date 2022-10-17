<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Receipt;
use Carbon\Traits\Date;
use App\Models\SalesPlan;
use App\Services\AccountRecevories\AccountRecevoryInterface;
use DataTables;
use Illuminate\Http\Request;
use App\Models\SalesPlanInstallments;
use App\DataTables\RecoverySalesPlanDataTable;

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
        $salesPlans = (new SalesPlan())->with(['unit', 'stakeholder', 'additionalCosts', 'installments', 'leadSource', 'receipts', 'unPaidInstallments'])->where(['status' => 1])->get();

        // dd($salesPlans);
        $events = [];
        foreach ($salesPlans as $key => $salesPlans) {
            // $events[] = [
            //     'id' => $salesPlans->id,
            //     'key' => $salesPlans->unPaidInstallments[$key]['details'],
            //     'allDay' => !1,
            //     'extendedProps' => [
            //         'calendar' => "Units",
            //     ],
            // ];


            foreach ($salesPlans->unPaidInstallments as $unPaidInstallments) {
                $events[] = [
                    'id' => $unPaidInstallments->id,
                    'title' => $salesPlans->unit->name. ' '. $salesPlans->unit->floor_unit_number . ' '. $unPaidInstallments->details. ' ( '.number_format($unPaidInstallments->amount). ' ) ',
                    'paid_amount' => number_format($unPaidInstallments->paid_amount),
                    'remaining_amount' => number_format($unPaidInstallments->remaining_amount),
                    'amount' => number_format($unPaidInstallments->amount),
                    'start' =>  $unPaidInstallments->date,
                    'end ' => $unPaidInstallments->date,
                    'allDay' => !0,
                    'extendedProps' => [
                        'calendar' => "Units",
                    ],
                ];
            }
        }
        return view('app.sites.accounts.recovery.calender')->with(
            [
                'events' => json_encode($events),
            ]
        );
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
