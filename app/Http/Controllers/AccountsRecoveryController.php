<?php

namespace App\Http\Controllers;

use DataTables;
use App\Models\Unit;
use App\Models\User;
use App\Models\Floor;
use App\Models\Receipt;
use Carbon\Traits\Date;
use App\Models\SalesPlan;
use App\Models\Stakeholder;
use Illuminate\Http\Request;
use App\Models\StakeholderType;
use App\Models\SalesPlanInstallments;
use App\DataTables\RecoverySalesPlanDataTable;
use App\Models\Type;
use App\Services\AccountRecevories\AccountRecevoryInterface;
use App\Services\Interfaces\FloorInterface;
use App\Services\Interfaces\UnitTypeInterface;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Services\User\Interface\UserInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

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
        $units = Unit::all();
        $floors = Floor::all();
        $users = User::all();
        $types = Type::all();
        $dealers = [];
        $dealer_stakeholders =  StakeholderType::where('type', 'D')->where('status', 1)->with('stakeholder')->get();
        $events = [];
        $stakeholders = [];

        foreach ($salesPlans as $key => $salesPlans) {
            $stakeholders[] = $salesPlans->stakeholder;

            foreach ($salesPlans->unPaidInstallments as $unPaidInstallments) {
                $events[] = [
                    'id' => $unPaidInstallments->id,
                    'title' => $salesPlans->unit->name . ' ' . $unPaidInstallments->details . ' ( ' . number_format($unPaidInstallments->amount) . ' ) ',
                    'paid_amount' => number_format($unPaidInstallments->paid_amount),
                    'remaining_amount' => number_format($unPaidInstallments->remaining_amount),
                    'amount' => number_format($unPaidInstallments->amount),
                    'start' =>  $unPaidInstallments->date,
                    'end ' => $unPaidInstallments->date,
                    'allDay' => !0,
                    'backgroundColor' => "#EEEDFD",
                    'textColor' => "#7367F0",
                    'extendedProps' => [
                        'calendar' => "all",
                    ],
                ];
            }
        }
        return view('app.sites.accounts.recovery.calender')->with(
            [
                'events' => json_encode($events),
                'units' => $units,
                'site_id' => $site_id,
                'stakeholders' => Stakeholder::all(),
                'floors' => $floors,
                'dealers' => $dealer_stakeholders,
                'users' => $users,
                'types' => $types,
            ]
        );
    }

    public function salesPlan(Request $request, $site_id)
    {
        if (request()->ajax()) {

            // Installments wise (1st, 2nd ...etc)

            // Expenses wise


            $filters = [];
            if ($request->has('filter_floors')) {
                $filters['filter_floors'] = $request->input('filter_floors');
            }
            if ($request->has('filter_unit')) {
                $filters['filter_unit'] = Str::of($request->input('filter_unit'))->upper()->trim();
            }
            if ($request->has('filter_customer')) {
                $filters['filter_customer'] = trim($request->input('filter_customer'));
            }
            if ($request->has('filter_dealer')) {
                $filters['filter_dealer'] = trim($request->input('filter_dealer'));
            }
            if ($request->has('filter_sale_source')) {
                $filters['filter_sale_source'] = trim($request->input('filter_sale_source'));
            }
            if ($request->has('filter_sale_source')) {
                $filters['filter_sale_source'] = trim($request->input('filter_sale_source'));
            }
            if ($request->has('filter_type')) {
                $filters['filter_type'] = trim($request->input('filter_type'));
            }
            if ($request->has('filter_generated_from') && $request->has('filter_generated_to')) {
                $filters['filter_generated_from'] = trim($request->input('filter_generated_from'));
                $filters['filter_generated_to'] = trim($request->input('filter_generated_to'));
            }
            if ($request->has('filter_approved_from') && $request->has('filter_approved_to')) {
                $filters['filter_approved_from'] = trim($request->input('filter_approved_from'));
                $filters['filter_approved_to'] = trim($request->input('filter_approved_to'));
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


    public function getFilteredUnitData(Request $request)
    {
        $unit_ids = $request->ids;
        $events = [];
        $maxInstallments = 0;

        if (isset($request->customer_ids)) {
            $stakeholder_ids = $request->customer_ids;
            for ($i = 0; $i < count($stakeholder_ids); $i++) {

                $salesPlans = (new SalesPlan())->with(['unit', 'stakeholder', 'unPaidInstallments'])->where(['status' => 1, 'stakeholder_id' => $stakeholder_ids[$i]])->get();

                foreach ($salesPlans as $key => $salesPlans) {

                    foreach ($salesPlans->unPaidInstallments as $unPaidInstallments) {
                        $events[] = [
                            'id' => $unPaidInstallments->id,
                            'title' => $salesPlans->stakeholder->full_name . ' ' . $salesPlans->unit->name . ' ' . $unPaidInstallments->details . ' ( ' . number_format($unPaidInstallments->amount) . ' ) ',
                            'paid_amount' => number_format($unPaidInstallments->paid_amount),
                            'remaining_amount' => number_format($unPaidInstallments->remaining_amount),
                            'amount' => number_format($unPaidInstallments->amount),
                            'start' =>  $unPaidInstallments->date,
                            'end ' => $unPaidInstallments->date,
                            'allDay' => !0,
                            'backgroundColor' => "#EEEDFD",
                            'textColor' => "#7367F0",
                            'extendedProps' => [
                                'calendar' => $salesPlans->unit->name,
                            ],
                        ];
                    }
                }
            }
        }

        // unit wise filter
        if (isset($unit_ids)) {
            for ($i = 0; $i < count($unit_ids); $i++) {

                $salesPlans = (new SalesPlan())->with(['unit', 'unPaidInstallments'])->where(['status' => 1, 'unit_id' => $unit_ids[$i]])->get();

                foreach ($salesPlans as $key => $salesPlans) {

                    foreach ($salesPlans->unPaidInstallments as $unPaidInstallments) {
                        $events[] = [
                            'id' => $unPaidInstallments->id,
                            'title' => $salesPlans->unit->name . ' '  . $unPaidInstallments->details . ' ( ' . number_format($unPaidInstallments->amount) . ' ) ',
                            'paid_amount' => number_format($unPaidInstallments->paid_amount),
                            'remaining_amount' => number_format($unPaidInstallments->remaining_amount),
                            'amount' => number_format($unPaidInstallments->amount),
                            'start' =>  $unPaidInstallments->date,
                            'end ' => $unPaidInstallments->date,
                            'allDay' => !0,
                            'backgroundColor' => "#EEEDFD",
                            'textColor' => "#7367F0",
                            'extendedProps' => [
                                'calendar' => $salesPlans->unit->name,
                            ],
                        ];
                    }
                }
            }
        }

        if (isset($request->floor_ids)) {
            $floorIds = $request->floor_ids;
            for ($i = 0; $i < count($floorIds); $i++) {
                $floor = Floor::find($floorIds[$i]);
                foreach ($floor->units as $unit) {
                    $salesPlans = (new SalesPlan())->with(['unit', 'unPaidInstallments'])->where(['status' => 1, 'unit_id' => $unit->id])->get();

                    foreach ($salesPlans as $key => $salesPlans) {

                        foreach ($salesPlans->unPaidInstallments as $unPaidInstallments) {
                            $events[] = [
                                'id' => $unPaidInstallments->id,
                                'title' => $floor->name . ' ' . $salesPlans->unit->name . ' '  . $unPaidInstallments->details . ' ( ' . number_format($unPaidInstallments->amount) . ' ) ',
                                'paid_amount' => number_format($unPaidInstallments->paid_amount),
                                'remaining_amount' => number_format($unPaidInstallments->remaining_amount),
                                'amount' => number_format($unPaidInstallments->amount),
                                'start' =>  $unPaidInstallments->date,
                                'end ' => $unPaidInstallments->date,
                                'allDay' => !0,
                                'backgroundColor' => "#7367f0",
                                'textColor' => "#ffffff",
                                'extendedProps' => [
                                    'calendar' => $salesPlans->unit->name,
                                ],
                            ];
                        }
                    }
                }
            }
        }


        // dealer wise filter
        if (isset($request->dealer_ids)) {
            $stakeholder_ids = $request->dealer_ids;
            for ($i = 0; $i < count($stakeholder_ids); $i++) {

                $salesPlans = (new SalesPlan())->with(['unit', 'stakeholder', 'unPaidInstallments'])->where(['status' => 1, 'stakeholder_id' => $stakeholder_ids[$i]])->get();

                foreach ($salesPlans as $key => $salesPlans) {

                    foreach ($salesPlans->unPaidInstallments as $unPaidInstallments) {
                        $events[] = [
                            'id' => $unPaidInstallments->id,
                            'title' => $salesPlans->stakeholder->full_name . ' ' . $salesPlans->unit->name . ' ' . $unPaidInstallments->details . ' ( ' . number_format($unPaidInstallments->amount) . ' ) ',
                            'paid_amount' => number_format($unPaidInstallments->paid_amount),
                            'remaining_amount' => number_format($unPaidInstallments->remaining_amount),
                            'amount' => number_format($unPaidInstallments->amount),
                            'start' =>  $unPaidInstallments->date,
                            'end ' => $unPaidInstallments->date,
                            'allDay' => !0,
                            'backgroundColor' => "#EEEDFD",
                            'textColor' => "#7367F0",
                            'extendedProps' => [
                                'calendar' => $salesPlans->unit->name,
                            ],
                        ];
                    }
                }
            }
        }

        // salesPerson filter

        if (isset($request->salesPerson_ids)) {
            $user_ids = [];
            $user_ids = $request->salesPerson_ids;
            for ($i = 0; $i < count($user_ids); $i++) {

                $user = User::find($user_ids[$i]);
                $salesPlans = (new SalesPlan())->with(['unit', 'stakeholder', 'unPaidInstallments'])->where(['status' => 1, 'user_id' => $user_ids[$i]])->get();
                foreach ($salesPlans as $key => $salesPlans) {

                    foreach ($salesPlans->unPaidInstallments as $unPaidInstallments) {
                        $events[] = [
                            'id' => $unPaidInstallments->id,
                            'title' => $user->name . ' ' . $salesPlans->unit->name . ' ' . $unPaidInstallments->details . ' ( ' . number_format($unPaidInstallments->amount) . ' ) ',
                            'paid_amount' => number_format($unPaidInstallments->paid_amount),
                            'remaining_amount' => number_format($unPaidInstallments->remaining_amount),
                            'amount' => number_format($unPaidInstallments->amount),
                            'start' =>  $unPaidInstallments->date,
                            'end ' => $unPaidInstallments->date,
                            'allDay' => !0,
                            'backgroundColor' => "#EEEDFD",
                            'textColor' => "#7367F0",
                            'extendedProps' => [
                                'calendar' => $salesPlans->unit->name,
                            ],
                        ];
                    }
                }
            }
        }

        //  unit type filter
        if (isset($request->type_ids)) {
            $typeIds = $request->type_ids;
            for ($i = 0; $i < count($typeIds); $i++) {
                $type = Type::find($typeIds[$i]);
                $unit = Unit::where('type_id', $typeIds[$i])->get();
                foreach ($unit as $unit) {
                    $salesPlans = (new SalesPlan())->with(['unit', 'unPaidInstallments'])->where(['status' => 1, 'unit_id' => $unit->id])->get();

                    foreach ($salesPlans as $key => $salesPlans) {

                        foreach ($salesPlans->unPaidInstallments as $unPaidInstallments) {
                            $events[] = [
                                'id' => $unPaidInstallments->id,
                                'title' =>  $type->name . ' ' .  $salesPlans->unit->name . ' '  . $unPaidInstallments->details . ' ( ' . number_format($unPaidInstallments->amount) . ' ) ',
                                'paid_amount' => number_format($unPaidInstallments->paid_amount),
                                'remaining_amount' => number_format($unPaidInstallments->remaining_amount),
                                'amount' => number_format($unPaidInstallments->amount),
                                'start' =>  $unPaidInstallments->date,
                                'end ' => $unPaidInstallments->date,
                                'allDay' => !0,
                                'backgroundColor' => "#7367f0",
                                'textColor' => "#ffffff",
                                'extendedProps' => [
                                    'calendar' => $salesPlans->unit->name,
                                ],
                            ];
                        }
                    }
                }
            }
        }

        // All Data Filter
        if (!isset($unit_ids) && !isset($request->customer_ids) && !isset($request->floor_ids) && !isset($request->dealer_ids) && !isset($request->salesPerson_ids) && !isset($request->type_ids)) {
            $salesPlans = (new SalesPlan())->with(['unit', 'stakeholder', 'additionalCosts', 'installments', 'leadSource', 'receipts', 'unPaidInstallments'])->where(['status' => 1])->get();

            foreach ($salesPlans as $key => $salesPlans) {
                $stakeholders[] = $salesPlans->stakeholder;

                foreach ($salesPlans->unPaidInstallments as $unPaidInstallments) {
                    $events[] = [
                        'id' => $unPaidInstallments->id,
                        'title' => $salesPlans->unit->name . ' ' . $unPaidInstallments->details . ' ( ' . number_format($unPaidInstallments->amount) . ' ) ',
                        'paid_amount' => number_format($unPaidInstallments->paid_amount),
                        'remaining_amount' => number_format($unPaidInstallments->remaining_amount),
                        'amount' => number_format($unPaidInstallments->amount),
                        'start' =>  $unPaidInstallments->date,
                        'end ' => $unPaidInstallments->date,
                        'allDay' => !0,
                        'backgroundColor' => "#7367f0",
                        'textColor' => "#ffffff",
                        'extendedProps' => [
                            'calendar' => "all",
                        ],
                    ];
                }
            }
        }

        return response()->json([
            'success' => true,
            'events' => $events,
        ], 200);
    }
}