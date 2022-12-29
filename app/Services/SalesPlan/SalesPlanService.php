<?php

namespace App\Services\SalesPlan;

use App\Exceptions\GeneralException;
use Exception;
use App\Models\User;
use Carbon\{Carbon, CarbonPeriod};
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\{URL, Auth, DB, Notification};
use Spatie\Permission\Models\Permission;
use App\Services\SalesPlan\Interface\SalesPlanInterface;
use App\Models\{
    AdditionalCost,
    SalesPlanAdditionalCost,
    Floor,
    LeadSource,
    SalesPlan,
    SalesPlanInstallments,
    Site,
    Stakeholder,
    StakeholderType,
    Unit
};
use App\Notifications\DefaultNotification;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Utils\Enums\StakeholderTypeEnum;
use Illuminate\Support\Str;
use Spatie\Activitylog\Facades\LogBatch;
use Spatie\Activitylog\Models\Activity;

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
    public function getByAll($site_id)
    {
        $site_id = decryptParams($site_id);

        return $this->model()->where('site_id', $site_id)->get();
    }

    public function getById($site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        return $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ])->first();
    }

    // Store
    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {

            LogBatch::startBatch();

            $site = (new Site())->find($site_id);
            $unit = (new Unit())->find($inputs['unit_id']);

            $authRoleId = auth()->user()->roles->pluck('id')->first();

            $approveSalesPlanPermission = (new Role())->find($authRoleId)->hasPermissionTo('sites.floors.units.sales-plans.approve-sales-plan');
            $permission = (new Permission())->where('name', 'sites.floors.units.sales-plans.approve-sales-plan')->first();

            $approveSalesPlanPermissionRole = $permission->roles;

            $stakeholderInput = $inputs['stackholder'];
            $individual = $inputs['individual'];

            $company = $inputs['company'];

            if (isset($individual['cnic']) && $stakeholderInput['stackholder_id'] == 0 && $inputs['stakeholder_as'] == 'i' && $this->stakeholderInterface->model()->where('cnic', $individual['cnic'])->exists()) {
                throw new GeneralException('Stakeholder CNIC already exists');
            }
            if (isset($company['cnic']) && $stakeholderInput['stackholder_id'] == 0 && $inputs['stakeholder_as'] == 'c' && $this->stakeholderInterface->model()->where('cnic', $company['cnic'])->exists()) {
                throw new GeneralException('Company Registration No already exists');
            }


            if (!isset($inputs['stakeholder_as'])) {
                $leadStakeholder = Stakeholder::find($stakeholderInput['stackholder_id']);
                $stakeholder_as = $leadStakeholder->stakeholder_as;
            } else {
                $stakeholder_as = $inputs['stakeholder_as'];
            }
            if ($stakeholder_as == 'i') {
                $stakeholderData = [
                    'full_name' => $individual['full_name'],
                    'father_name' => $individual['father_name'],
                    'occupation' => $individual['occupation'],
                    'designation' => $individual['designation'],
                    'cnic' => $individual['cnic'],
                    'passport_no' => $individual['passport_no'],
                    'ntn' => $individual['ntn'],
                    'email' => $individual['individual_email'],
                    'office_email' => $individual['office_email'],
                    'mobile_contact' => $individual['mobile_contact'],
                    'mobileContactCountryDetails' => $inputs['mobileContactCountryDetails'],
                    'office_contact' => $individual['office_contact'],
                    'OfficeContactCountryDetails' => $inputs['OfficeContactCountryDetails'],
                    'referred_by' => $individual['referred_by'],
                    'source' => $individual['source'] ?? 0,
                    'date_of_birth' => $individual['dob'],
                    'is_local' => $individual['is_local'],
                    'nationality' => $individual['nationality'],
                ];
            } else if ($stakeholder_as == 'c') {
                $stakeholderData = [
                    'full_name' => $company['company_name'],
                    'industry' => $company['industry'],
                    'office_contact' => $company['company_office_contact'],
                    'OfficeContactCountryDetails' => $inputs['CompanyOfficeContactCountryDetails'],
                    'mobile_contact' => $company['company_optional_contact'],
                    'mobileContactCountryDetails' => $inputs['companyMobileContactCountryDetails'],
                    'email' => $company['company_email'],
                    'office_email' => $company['company_office_email'],
                    'website' => $company['website'],
                    'parent_company' => $company['parent_company'],
                    'cnic' => $company['registration'],
                    'strn' => $company['strn'],
                    'ntn' => $company['company_ntn'],
                    'origin' => $company['origin'],
                ];
            }
            $stakeholderData['stakeholder_as'] = $stakeholder_as;
            $stakeholderData['site_id'] = $site_id;

            // residential address fields
            $residential = $inputs['residential'];
            $stakeholderData['residential_address_type'] = $residential['address_type'];
            $stakeholderData['residential_address'] = $residential['address'];
            $stakeholderData['residential_postal_code'] = $residential['postal_code'];
            $stakeholderData['residential_country_id'] = $residential['country'] > 0 ? $residential['country'] : 167;
            $stakeholderData['residential_state_id'] =  $residential['state'];
            $stakeholderData['residential_city_id'] =  $residential['city'];

            //mailing address fields
            $mailing = $inputs['mailing'];
            $stakeholderData['mailing_address_type'] = $mailing['address_type'];
            $stakeholderData['mailing_address'] = $mailing['address'];
            $stakeholderData['mailing_postal_code'] = $mailing['postal_code'];
            $stakeholderData['mailing_country_id'] = $mailing['country'] > 0 ? $mailing['country'] : 167;
            $stakeholderData['mailing_state_id'] = $mailing['state'];
            $stakeholderData['mailing_city_id'] = $mailing['city'];

            $stakeholderData['comments'] = $inputs['comments'];

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

            $unit = (new Unit())->where('floor_unit_number', $inputs['unit']['no'])->first();

            $unitInput = $inputs['unit'];

            $leadSource = $inputs['sales_source'];

            if ($leadSource['lead_source'] == 0) {

                $leadSourceData = [
                    'site_id' => $site->id,
                    'name' => $leadSource['new'],
                ];

                $leadSource = (new LeadSource())->create($leadSourceData);
            }


            $serail_no = $this->model()::max('id') + 1;
            $serail_no =  sprintf('%03d', $serail_no);


            $sales_plan_data = [
                'unit_id' => $unit->id,
                'user_id' => auth()->user()->id,
                'stakeholder_id' => $stakeholder->id,
                'stakeholder_data' => json_encode(Stakeholder::find($stakeholder->id)),
                'unit_price' => $unitInput['price']['unit'],
                'total_price' => floatval(str_replace(',', '', $unitInput['grand_total'])),
                'discount_percentage' => $unitInput['discount']['percentage'],
                'discount_total' => floatval(str_replace(',', '', $unitInput['discount']['total'])),
                'down_payment_percentage' => $unitInput['downpayment']['percentage'],
                'down_payment_total' => floatval(str_replace(',', '', $unitInput['downpayment']['total'])),
                'lead_source_id' => ($leadSource['lead_source'] == 0) ? $leadSource->id : $leadSource['lead_source'],
                'validity' => $inputs['sales_plan_validity'],
                'comments' => $inputs['sale_plan_comments'],
                'status' => false,
                'created_date' => $inputs['created_date'] . date(' H:i:s'),
                'serial_no' => 'SI-' . $serail_no,
            ];
            // dd(json_encode($stakeholderInput['next_of_kin']));

            if(Auth::user()->hasRole('CRM')){
                $sales_plan_data['is_from_crm'] = true;
            }
            if (isset($stakeholderInput['next_of_kin'])) {

                $sales_plan_data['kin_data'] = json_encode($stakeholderInput['next_of_kin']);
            }

            $salesPlan = $this->model()->create($sales_plan_data);
            if (isset($inputs['unit']['additional_cost'])) {
                $additionalCosts = $inputs['unit']['additional_cost'];

                foreach ($additionalCosts as $key => $value) {
                    if ($value['status'] == 'true') {
                        $additonalCost = (new AdditionalCost())->where('slug', $key)->first();

                        $additionalCostData = [
                            'sales_plan_id' => $salesPlan->id,
                            'additional_cost_id' => $additonalCost->id,
                            'percentage' => $value['percentage'],
                            'amount' => str_replace(',', '', $value['total']),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        (new SalesPlanAdditionalCost())->create($additionalCostData);
                    }
                }
            }
            $downpaymentTotal = $inputs['unit']['downpayment']['total'];
            $installments = $inputs['installments']['table'];
            $installmentsData = [];

            $installmentsData[] = [
                'sales_plan_id' => $salesPlan->id,
                'date' => $inputs['created_date'] . date(' H:i:s'),
                'details' => 'Downpayment',
                'type' => 'downpayment',
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
                    'type' => 'installment',
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
                        'type' => 'additional_expense',
                        'paid_amount' => 0,
                        'remaining_amount' => floatval($expense['amount']),
                        'remarks' => $expense['remarks'],
                        'installment_order' => $count,
                        'status' => 'unpaid',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];

                    $count++;
                }
            }

            foreach ($installmentsData as $installment) {
                (new SalesPlanInstallments())->create($installment);
            }

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

            // Notification::send($specificUsers, new DefaultNotification($notificaionData));
            LogBatch::endBatch();
            return $salesPlan;
        });
    }

    public function generateInstallments($site_id, $floor_id, $unit_id, $inputs)
    {
        try {
            $start = microtime(true);
            $unit = (new Unit())->find($inputs['unit_id']);
            $installments = [
                'site' => (new Site())->find(decryptParams($site_id)),
                'floor' => $unit->floor,
                'unit' => $unit,
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
