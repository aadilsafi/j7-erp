<?php

namespace App\Services\SalesPlan;

use Exception;
use App\Models\User;
use Carbon\{Carbon, CarbonPeriod};
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\LazyCollection;
use Spatie\Permission\Models\Permission;
use App\Services\SalesPlan\Interface\SalesPlanInterface;
use App\Jobs\SalesPlan\GeneratedSalesPlanNotificationJob;
use App\Models\{Stakeholder, AdditionalCost, SalesPlanAdditionalCost, Floor, SalesPlan, SalesPlanInstallments, Site, Unit};

class SalesPlanService implements SalesPlanInterface
{

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
    public function store($site_id, $inputs)
    {
        $authRoleId = Auth::user()->roles->pluck('id');
        $approveSalesPlanPermission = Role::find($authRoleId[0])->hasPermissionTo('sites.floors.units.sales-plans.approve-sales-plan');

        $permission = Permission::where('name','sites.floors.units.sales-plans.approve-sales-plan')->first();
        $approveSalesPlanPermissionRole = $permission->roles;

        if($approveSalesPlanPermission){
            $status = true;
        }
        else
        {
            $status = false;
        }

        $stakeholderData = [
            'site_id' => decryptParams($site_id),
            'full_name' => $inputs['stackholder']['full_name'],
            'farther_name' => $inputs['stackholder']['father_name'],
            'occupation' => $inputs['stackholder']['occupation'],
            'designation' => $inputs['stackholder']['designation'],
            'cnic' => $inputs['stackholder']['cnic'],
            'contact' => $inputs['stackholder']['contact'],
            'address' => $inputs['stackholder']['address'],
        ];

        $stakeholder_data = Stakeholder::updateOrCreate(
            [
                'id' => $inputs['stackholder']['stackholder_id'],
            ],
            $stakeholderData
        );

        $unit_id = Unit::where('floor_unit_number',$inputs['unit']['no'])->first()->id;
        $sales_plan_data = [
            'unit_id' => $unit_id,
            'stakeholder_id' => $stakeholder_data->id,
            'user_id' => auth()->user()->id,
            'unit_price' => $inputs['unit']['price']['unit'],
            'total_price' => str_replace(',', '', $inputs['unit']['price']['total']),
            'discount_percentage' => $inputs['unit']['discount']['percentage'],
            'discount_total' => str_replace(',', '', $inputs['unit']['discount']['total']),
            'down_payment_percentage' => $inputs['unit']['downpayment']['percentage'],
            'down_payment_total' => str_replace(',', '', $inputs['unit']['downpayment']['total']),
            'sales_type' => $inputs['sales_source']['lead_source'],
            // 'indirect_source' => $inputs['sales_source']['indirect_source'],
            'validity' => $inputs['sales_plan_validity'],
            'status' => $status,
        ];

        $sales_plan = $this->model()->create($sales_plan_data);

        foreach($inputs['unit']['additional_cost'] as $key => $value){
            $additonal_cost_id =  AdditionalCost::where('slug',$key)->first()->id;

            $sales_plan_additonal_cost_data = [
                'sales_plan_id' => $sales_plan->id,
                'additional_cost_id' => $additonal_cost_id,
                'percentage' => $value['percentage'],
                'amount' => str_replace(',', '', $value['total']),
            ];

            $sales_plan_additonal_cost = SalesPlanAdditionalCost::create($sales_plan_additonal_cost_data);

        }

        foreach($inputs['installments']['table'] as $key => $table_data){

            $instalmentData = [
                'sales_plan_id' =>  $sales_plan->id,
                'date' => Carbon::parse(str_replace('/', '-', $table_data['date'])),
                // 'details' => $table_data['details'],
                'amount' => str_replace(',', '', $table_data['amount']),
                'remarks' => $table_data['remarks'],
            ];

            $SalesPlanInstallments = SalesPlanInstallments::create($instalmentData);

        }

        $specificUsers = collect();
        foreach($approveSalesPlanPermissionRole as $role){
            $specificUsers = $specificUsers->merge(User::role($role->name)->whereNot('id' , Auth::user()->id)->get());

        }
        $currentURL = URL::current();

        $notificaionData = [
            'title' => 'New Sales Plan Genration Notification',
            'description' => Auth::User()->name.' generated new sales plan',
            'message' => 'xyz message',
            'url' => str_replace('/store', '', $currentURL),
        ];

        GeneratedSalesPlanNotificationJob::dispatch($notificaionData,$specificUsers)->delay(Carbon::now()->addMinutes(1));

        return $sales_plan;

    }

    // public function storeInBulk($site_id, $user_id, $inputs, $isFloorActive = false)
    // {
    //     FloorCopyMainJob::dispatch($site_id, $user_id, $inputs, $isFloorActive);
    //     return true;
    // }

    // public function update($site_id, $id, $inputs)
    // {
    //     $site_id = decryptParams($site_id);
    //     $id = decryptParams($id);

    //     $data = [
    //         'site_id' => $site_id,
    //         'name' => filter_strip_tags($inputs['name']),
    //         'short_label' => Str::of(filter_strip_tags($inputs['short_label']))->upper(),
    //         'floor_area' => filter_strip_tags($inputs['floor_area']),
    //         'order' => filter_strip_tags($inputs['floor_order']),
    //     ];

    //     $floor = $this->model()->where([
    //         'site_id' => $site_id,
    //         'id' => $id,
    //     ])->update($data);

    //     return $floor;
    // }

    // public function destroy($site_id, $id)
    // {
    //     $site_id = decryptParams($site_id);
    //     $id = decryptParams($id);

    //     $this->model()->where([
    //         'site_id' => $site_id,
    //         'id' => $id,
    //     ])->delete();

    //     return true;
    // }

    public function generateInstallments($site_id, $floor_id, $unit_id, $inputs)
    {
        try {
            $start = microtime(true);

            $installments = [
                'site' => (new Site())->find(decryptParams($site_id)),
                'floor' => (new Floor())->find(decryptParams($floor_id)),
                'unit' => (new Unit())->find(decryptParams($unit_id)),
            ];

            $unchangedData = isset($inputs['unchangedData']) ? $inputs['unchangedData'] : [];

            $installmentDates = $this->dateRanges($inputs['startDate'], $inputs['length'], $inputs['rangeCount'], $inputs['rangeBy']);

            $unchangedData = LazyCollection::make($unchangedData);

            $unchangedAmont = $unchangedData->where('field', 'amount')->sum('value');
            $unchangedAmontCount = $unchangedData->where('field', 'amount')->count();

            $baseInstallmentTotal = ($inputs['installment_amount'] - $unchangedAmont);

            if ($baseInstallmentTotal < 0) {
                throw new Exception('Invalid Amount!');
            }

            $amount = $this->baseInstallment($baseInstallmentTotal, ($inputs['length'] - $unchangedAmontCount));

            $installments['installments'] = collect($installmentDates)->map(function ($date, $key) use ($amount, $unchangedData, $baseInstallmentTotal) {

                $filteredData = $unchangedData->where('key', $key + 1)->whereIn('field', ['details', 'amount', 'remarks'])->map(function ($item) {
                    return [$item['field'] => $item['value']];
                })->values()->reduce(function ($carry, $item) {
                    return array_merge($carry, $item);
                }, []);

                $installmentRow = [
                    'key' => $key + 1,
                    'date' => $date,
                    'detail' => null,
                    'amount' => $amount,
                    'remarks' => null,
                ];

                $rowData = [
                    'key' => $installmentRow['key'],
                    'keyShow' => true,
                    'date' => $installmentRow['date'],
                    'dateShow' => true,
                    'detail' => $installmentRow['detail'],
                    'detailShow' => true,
                    'amount' => $installmentRow['amount'],
                    'amountName' => true,
                    'amountShow' => true,
                    'amountReadonly' => false,
                    'remarks' => $installmentRow['remarks'],
                    'remarksShow' => true,
                    'filteredData' => $filteredData,
                    'baseInstallmentTotal' => $baseInstallmentTotal,
                ];

                $installmentRow['row'] = view('app.sites.floors.units.sales-plan.partials.installment-table-row', $rowData)->render();

                return $installmentRow;
            })->toArray();

            if ($inputs['length'] > 0) {
                $installments['installments'][] = [
                    'row' => $installmentRow['row'] = view('app.sites.floors.units.sales-plan.partials.installment-table-row', [
                        'key' => 'total',
                        'keyShow' => false,
                        'date' => '',
                        'dateShow' => false,
                        'detail' => '',
                        'detailShow' => false,
                        'amount' => number_format($inputs['installment_amount']),
                        'amountName' => false,
                        'amountShow' => true,
                        'amountReadonly' => true,
                        'remarks' => '',
                        'remarksShow' => false,
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
