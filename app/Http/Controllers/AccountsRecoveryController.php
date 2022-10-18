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
use App\Services\Interfaces\FloorInterface;
use App\Services\Interfaces\UnitTypeInterface;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Services\User\Interface\UserInterface;
use Illuminate\Support\Facades\Log;

class AccountsRecoveryController extends Controller
{

    private $accountRecevoryInterface, $floorInterface, $stakeholderInterface, $userInterface, $unitTypeInterface;

    public function __construct(
        AccountRecevoryInterface $accountRecevoryInterface,
        FloorInterface $floorInterface,
        StakeholderInterface $stakeholderInterface,
        UserInterface $userInterface,
        UnitTypeInterface $unitTypeInterface,
    ) {
        $this->accountRecevoryInterface = $accountRecevoryInterface;
        $this->floorInterface = $floorInterface;
        $this->stakeholderInterface = $stakeholderInterface;
        $this->userInterface = $userInterface;
        $this->unitTypeInterface = $unitTypeInterface;
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
                    'title' => $salesPlans->unit->name . ' ' . $salesPlans->unit->floor_unit_number . ' ' . $unPaidInstallments->details . ' ( ' . number_format($unPaidInstallments->amount) . ' ) ',
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

    public function salesPlan(Request $request, $site_id)
    {
        if (request()->ajax()) {

            // Show data by filters in calendars


            // Date wise

            // Installments wise (1st, 2nd ...etc)

            // Categories (shops, suits... etc)

            // Expenses wise


            $filters = [];
            if ($request->has('filter_floors')) {
                $filters['filter_floors'] = $request->input('filter_floors');
            }
            if ($request->has('filter_unit')) {
                $filters['filter_unit'] = $request->input('filter_unit');
            }
            if ($request->has('filter_customer')) {
                $filters['filter_customer'] = $request->input('filter_customer');
            }
            if ($request->has('filter_dealer')) {
                $filters['filter_dealer'] = $request->input('filter_dealer');
            }
            if ($request->has('filter_sale_source')) {
                $filters['filter_sale_source'] = $request->input('filter_sale_source');
            }
            if ($request->has('filter_sale_source')) {
                $filters['filter_sale_source'] = $request->input('filter_sale_source');
            }
            if ($request->has('filter_type')) {
                $filters['filter_type'] = $request->input('filter_type');
            }

            $dataTable = $this->accountRecevoryInterface->generateDataTable($site_id, $filters);
            return DataTables::of($dataTable)->make(true);
        }

        $salesPlans = (new SalesPlan())->with(['installments'])->where(['status' => 1])->get();

        $maxInstallments = collect($salesPlans)->transform(function ($salesPlan) {
            return $salesPlan->installments->where('type', 'installment')->count();
        })->max();

        $data = [
            'site_id' => decryptParams($site_id),
            'salesPlans' => $salesPlans,
            'max_installments' => $maxInstallments,
            'floors' => $this->floorInterface->getByAll($site_id),
            'stakeholders' => $this->stakeholderInterface->getByAllWith(decryptParams($site_id), [
                'stakeholder_types'
            ]),
            'users' => $this->userInterface->getByAll(decryptParams($site_id)),
            'types' => $this->unitTypeInterface->getAllWithTree(decryptParams($site_id))
        ];

        // dd($data);

        return view('app.sites.accounts.recovery.sales-plan', $data);
    }
}
