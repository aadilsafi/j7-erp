<?php

namespace App\Services\SalesPlan;

use Exception;
use App\Models\User;
use Carbon\{Carbon, CarbonPeriod};
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\{URL, Auth};
use Spatie\Permission\Models\Permission;
use App\Services\SalesPlan\Interface\SalesPlanInterface;
use App\Jobs\SalesPlan\GeneratedSalesPlanNotificationJob;
use App\Models\{
    AdditionalCost,
    SalesPlanAdditionalCost,
    Floor,
    LeadSource,
    SalesPlan,
    SalesPlanInstallments,
    Site,
    StakeholderType,
    Unit
};
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Utils\Enums\StakeholderTypeEnum;
use Illuminate\Support\Str;

class SalesPlanService implements SalesPlanInterface
{

    private $stakeholderInterface;

    public function __construct(StakeholderInterface $stakeholderInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
    }

    public function model()
    {
        return new SalesPlan();
    }

    // // Get
    // public function getByAll($site_id)
    // {
    //     $site_id = decryptParams($site_id);

    //     return $this->model()->where('site_id', $site_id)->get();
    // }

    // public function getById($site_id, $id)
    // {
    //     $site_id = decryptParams($site_id);
    //     $id = decryptParams($id);

    //     return $this->model()->where([
    //         'site_id' => $site_id,
    //         'id' => $id,
    //     ])->first();
    // }

    // // Store
    public function store($site_id, $floor_id, $unit_id, $inputs)
    {

        $site = (new Site())->find($site_id);
        $floor = (new Floor())->find($floor_id);
        $unit = (new Unit())->find($unit_id);

        // dd($unit);

        $authRoleId = auth()->user()->roles->pluck('id')->first();

        $approveSalesPlanPermission = (new Role())->find($authRoleId)->hasPermissionTo('sites.floors.units.sales-plans.approve-sales-plan');
        $permission = (new Permission())->where('name', 'sites.floors.units.sales-plans.approve-sales-plan')->first();

        $approveSalesPlanPermissionRole = $permission->roles;

        $stakeholderInput = $inputs['stackholder'];

        $stakeholderData = [
            'site_id' => $site->id,
            'full_name' => $stakeholderInput['full_name'],
            'father_name' => $stakeholderInput['father_name'],
            'occupation' => $stakeholderInput['occupation'],
            'designation' => $stakeholderInput['designation'],
            'ntn' => $stakeholderInput['ntn'],
            'cnic' => $stakeholderInput['cnic'],
            'contact' => $stakeholderInput['contact'],
            'address' => $stakeholderInput['address'],
        ];

        $stakeholder = $this->stakeholderInterface->model()->updateOrCreate([
            'id' => $stakeholderInput['stackholder_id'],
        ], $stakeholderData);

        if ($stakeholderInput['stackholder_id'] == 0) {
            $stakeholderTypeCode = Str::of($stakeholder->id)->padLeft(3, '0');
            $stakeholderTypeData  = [
                [
                    'stakeholder_id' => $stakeholder->id,
                    'type' => StakeholderTypeEnum::CUSTOMER->value,
                    'stakeholder_code' => StakeholderTypeEnum::CUSTOMER->value . '-' . $stakeholderTypeCode,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'stakeholder_id' => $stakeholder->id,
                    'type' => StakeholderTypeEnum::VENDOR->value,
                    'stakeholder_code' => StakeholderTypeEnum::VENDOR->value . '-' . $stakeholderTypeCode,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'stakeholder_id' => $stakeholder->id,
                    'type' => StakeholderTypeEnum::DEALER->value,
                    'stakeholder_code' => StakeholderTypeEnum::DEALER->value . '-' . $stakeholderTypeCode,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'stakeholder_id' => $stakeholder->id,
                    'type' => StakeholderTypeEnum::NEXT_OF_KIN->value,
                    'stakeholder_code' => StakeholderTypeEnum::NEXT_OF_KIN->value . '-' . $stakeholderTypeCode,
                    'status' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
                [
                    'stakeholder_id' => $stakeholder->id,
                    'type' => StakeholderTypeEnum::LEAD->value,
                    'stakeholder_code' => StakeholderTypeEnum::LEAD->value . '-' . $stakeholderTypeCode,
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ],
            ];
            $stakeholderType = (new StakeholderType())->insert($stakeholderTypeData);
        }

        // $unit = (new Unit())->where('floor_unit_number', $inputs['unit']['no'])->first();

        $unitInput = $inputs['unit'];

        $leadSource = $inputs['sales_source'];

        if ($leadSource['lead_source'] == 0) {

            $leadSourceData = [
                'site_id' => $site->id,
                'name' => $leadSource['new'],
            ];

            $leadSource = (new LeadSource())->create($leadSourceData);
        }

        $sales_plan_data = [
            'unit_id' => $unit->id,
            'user_id' => auth()->user()->id,
            'stakeholder_id' => $stakeholder->id,
            'stakeholder_data' => json_encode($stakeholder),
            'unit_price' => $unitInput['price']['unit'],
            'total_price' => intval(str_replace(',', '', $unitInput['price']['total'])),
            'discount_percentage' => $unitInput['discount']['percentage'],
            'discount_total' => intval(str_replace(',', '', $unitInput['discount']['total'])),
            'down_payment_percentage' => $unitInput['downpayment']['percentage'],
            'down_payment_total' => intval(str_replace(',', '', $unitInput['downpayment']['total'])),
            'lead_source_id' => ($leadSource['lead_source'] == 0) ? $leadSource->id : $leadSource['lead_source'],
            'validity' => $inputs['sales_plan_validity'],
            'comments' => $inputs['comments']['custom'],
            'status' => false,
        ];

        $salesPlan = $this->model()->create($sales_plan_data);

        $additionalCosts = $inputs['unit']['additional_cost'];
        $additionalCostData = [];
        foreach ($additionalCosts as $key => $value) {
            if ($value['status'] == 'true') {
                $additonalCost = (new AdditionalCost())->where('slug', $key)->first();

                $additionalCostData[] = [
                    'sales_plan_id' => $salesPlan->id,
                    'additional_cost_id' => $additonalCost->id,
                    'percentage' => $value['percentage'],
                    'amount' => str_replace(',', '', $value['total']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        // dd($additionalCostData);
        $salesPlanAdditionalCosts = (new SalesPlanAdditionalCost())->insert($additionalCostData);

        $downpaymentTotal = $inputs['unit']['downpayment']['total'];
        $installments = $inputs['installments']['table'];
        $installmentsData = [];

        $installmentsData[] = [
            'sales_plan_id' => $salesPlan->id,
            'date' => now(),
            'details' => 'Downpayment',
            'amount' => floatval(str_replace(',', '', $downpaymentTotal)),
            'paid_amount' => 0,
            'remaining_amount' => floatval(str_replace(',', '', $downpaymentTotal)),
            'remarks' => null,
            'installment_order' => 0,
            'status' => 'unpaid',
            'created_at' => now(),
            'updated_at' => now(),
        ];

        array_pop($installments);
        foreach ($installments as $key => $installment) {
            $installmentsData[] = [
                'sales_plan_id' => $salesPlan->id,
                'date' => Carbon::parse($installment['due_date']),
                'details' => $installment['installment'],
                'amount' => floatval($installment['total_amount']),
                'paid_amount' => 0,
                'remaining_amount' => floatval($installment['total_amount']),
                'remarks' => $installment['remarks'],
                'installment_order' => $key,
                'status' => 'unpaid',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        if (isset($inputs['expenses'])) {
            $expenses = $inputs['expenses'];
            $count = count($installmentsData);
            foreach ($expenses as $key => $expense) {
                $installmentsData[] = [
                    'sales_plan_id' => $salesPlan->id,
                    'date' => Carbon::parse($expense['due_date']),
                    'details' => $expense['expense_label'],
                    'amount' => floatval($expense['amount']),
                    'paid_amount' => 0,
                    'remaining_amount' => floatval($expense['expense_label']),
                    'remarks' => $expense['remarks'],
                    'installment_order' => $count,
                    'status' => 'unpaid',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $count++;
            }
        }
        // dd($installmentsData);

        $SalesPlanInstallments = (new SalesPlanInstallments())->insert($installmentsData);

        //Notification of Sales Plan
        $specificUsers = collect();
        foreach ($approveSalesPlanPermissionRole as $role) {
            $specificUsers = $specificUsers->merge(User::role($role->name)->whereNot('id', Auth::user()->id)->get());
        }
        $currentURL = URL::current();

        $notificaionData = [
            'title' => 'New Sales Plan Genration Notification',
            'description' => Auth::User()->name . ' generated new sales plan',
            'message' => 'xyz message',
            'url' => str_replace('/store', '', $currentURL),
        ];

        GeneratedSalesPlanNotificationJob::dispatch($notificaionData, $specificUsers)->delay(Carbon::now()->addMinutes(1));

        return $salesPlan;
    }

    public function generateInstallments($site_id, $floor_id, $unit_id, $inputs)
    {
        try {
            $start = microtime(true);

            $installments = [
                'site' => (new Site())->find(decryptParams($site_id)),
                'floor' => (new Floor())->find(decryptParams($floor_id)),
                'unit' => (new Unit())->find(decryptParams($unit_id)),
            ];

            $unchangedData = collect(isset($inputs['unchangedData']) ? $inputs['unchangedData'] : []);

            $unchangedAmont = $unchangedData->where('field', 'amount')->sum('value');

            $unchangedAmontCount = $unchangedData->where('field', 'amount')->count();

            $baseInstallmentTotal = ($inputs['installment_amount'] - $unchangedAmont);

            if ($baseInstallmentTotal < 0) {
                throw new Exception('invalid_amount');
            }

            $amount = $this->baseInstallment($baseInstallmentTotal, ($inputs['length'] - $unchangedAmontCount));

            $unchangedDates = $unchangedData->where('field', 'due_date')->sortBy('key');

            $installmentDates = $this->installmentDataCalculation($inputs['startDate'], 1, intval($inputs['length']), $inputs['rangeCount'], $unchangedDates->pluck('value')->all());

            $unchangedDates = $unchangedDates->values()->all();

            $totalInstallmentAmount = 0;

            $installments['installments'] = collect($installmentDates)->map(function ($date, $key) use (
                $amount,
                $unchangedData,
                $baseInstallmentTotal,
                $unchangedDates,
                &$totalInstallmentAmount,
            ) {

                $filteredData = $unchangedData->where('key', $key + 1)->whereIn('field', ['amount', 'remarks'])->map(function ($item) {
                    return [$item['field'] => $item['value']];
                })->values()->reduce(function ($carry, $item) {
                    return array_merge($carry, $item);
                }, []);

                $installmentRow = [
                    'key' => $key + 1,
                    'date' => $date,
                    'amount' => $amount,
                    'remarks' => null,
                ];

                $rowFields = [
                    'index' => [
                        'value' => $installmentRow['key'],
                        'classes' => '',
                        'placeholder' => 'Index',
                        'name' => true,
                        'show' => true,
                        'disabled' => false,
                        'readonly' => false,
                    ],
                    'installments' => [
                        'value' => englishCounting($installmentRow['key']) . ' Installment',
                        'classes' => '',
                        'placeholder' => 'Installments',
                        'name' => true,
                        'show' => true,
                        'disabled' => false,
                        'readonly' => true,
                    ],
                    'due_date' => [
                        'value' => (new Carbon($installmentRow['date']))->format('Y-m-d'),
                        'classes' => '',
                        'placeholder' => 'Due Date',
                        'name' => true,
                        'show' => true,
                        'disabled' => false,
                        'readonly' => true,
                    ],
                    'total_amount' => [
                        'value' => isset($filteredData['amount']) ? $filteredData['amount'] : $installmentRow['amount'],
                        'classes' => '',
                        'placeholder' => 'Total Amount',
                        'name' => true,
                        'show' => true,
                        'disabled' => false,
                        'readonly' => false,
                    ],
                    'remarks' => [
                        'value' => isset($filteredData['remarks']) ? $filteredData['remarks'] : $installmentRow['remarks'],
                        'classes' => '',
                        'placeholder' => 'Remarks',
                        'name' => true,
                        'show' => true,
                        'disabled' => false,
                        'readonly' => false,
                    ],
                    'others' => [
                        'value' => '',
                        'classes' => '',
                        'placeholder' => 'Others',
                        'name' => true,
                        'show' => true,
                        'disabled' => false,
                        'readonly' => false,
                    ],
                    'filteredData' => $filteredData,
                    'baseInstallmentTotal' => $baseInstallmentTotal,
                ];

                if ($key > 0 && isset($unchangedDates[$key - 1])) {
                    $rowFields['due_date']['minDate'] = $unchangedDates[$key - 1]['value'];
                    $rowFields['due_date']['readonly'] = false;
                }
                if ($key == 0) {
                    $rowFields['due_date']['readonly'] = false;
                }


                $totalInstallmentAmount += floatval($rowFields['total_amount']['value']);
                $installmentRow['row'] = view('app.sites.floors.units.sales-plan.partials.installment-table-row', $rowFields)->render();

                return $installmentRow;
            })->toArray();

            if ($inputs['length'] > 0) {
                $installments['installments'][] = [
                    'row' => $installmentRow['row'] = view('app.sites.floors.units.sales-plan.partials.installment-table-row', [
                        'index' => [
                            'value' => '',
                            'classes' => '',
                            'placeholder' => 'Index',
                            'name' => false,
                            'show' => false,
                            'disabled' => true,
                            'readonly' => true,
                        ],
                        'installments' => [
                            'value' => '',
                            'classes' => '',
                            'placeholder' => 'Installments',
                            'name' => false,
                            'show' => false,
                            'disabled' => true,
                            'readonly' => true,
                        ],
                        'due_date' => [
                            'value' => '',
                            'classes' => '',
                            'placeholder' => 'Due Date',
                            'name' => false,
                            'show' => false,
                            'disabled' => true,
                            'readonly' => true,
                        ],
                        'total_amount' => [
                            'value' => $totalInstallmentAmount,
                            'classes' => '',
                            'placeholder' => 'Total Amount',
                            'name' => true,
                            'show' => true,
                            'disabled' => false,
                            'readonly' => true,
                        ],
                        'remarks' => [
                            'value' => '',
                            'classes' => '',
                            'placeholder' => 'Remarks',
                            'name' => false,
                            'show' => false,
                            'disabled' => true,
                            'readonly' => true,
                        ],
                        'others' => [
                            'value' => '',
                            'classes' => '',
                            'placeholder' => 'Others',
                            'name' => false,
                            'show' => false,
                            'disabled' => true,
                            'readonly' => true,
                        ],
                        'filteredData' => [],
                        'baseInstallmentTotal' => $baseInstallmentTotal,
                    ])->render(),
                ];
            }

            $installments['baseInstallmentTotal'] = $baseInstallmentTotal > 0 ? $baseInstallmentTotal : 0;

            $time_elapsed_secs = microtime(true) - $start;

            return $installments;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    private function installmentDataCalculation($date, $indexStart, $indexEnd, $numberOfDays, $datesList = [])
    {
        if ($indexEnd - $indexStart < 0) {
            return [];
        }

        $date = isset($datesList[$indexStart - 1]) ? $datesList[$indexStart - 1] : $date;

        $data = [
            $date,
        ];

        if ($indexStart == $indexEnd) {
            return $data;
        }

        $date = (new Carbon($date))->addDays($numberOfDays)->format('Y-m-d');
        $indexStart++;

        $data = array_merge($data, $this->installmentDataCalculation($date, $indexStart, $indexEnd, $numberOfDays, $datesList));
        return $data;
    }

    private function baseInstallment($total, $divide)
    {
        if ($divide == 0) {
            return 0;
        }
        return $total / $divide;
    }

    private function dateRanges($requrestDate, $length = 1, $daysCount = 1, $rangeBy = 'days')
    {
        $startDate = Carbon::parse($requrestDate);

        $endDate =  (new Carbon($requrestDate))->add((($length - 1) * $daysCount), $rangeBy);

        $period = CarbonPeriod::create($startDate, ($daysCount . ' ' . $rangeBy), $endDate);

        $dates = $period->toArray();

        return $dates;
    }
}
