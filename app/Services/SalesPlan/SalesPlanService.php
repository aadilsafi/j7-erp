<?php

namespace App\Services\SalesPlan;

use App\Models\Stakeholder;
use App\Models\AdditionalCost;
use Carbon\{Carbon, CarbonPeriod};
use Illuminate\Support\LazyCollection;
use App\Models\SalesPlanAdditionalCost;
use App\Models\{Floor, SalesPlan, Site, Unit};
use App\Services\SalesPlan\Interface\SalesPlanInterface;

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
    public function store($inputs,$site_id)
    {
        // dd($inputs['unit']);

        if($inputs['stackholder']['stackholder_id'] == 0){
            $stakeholder_data = new Stakeholder;
        }
        else{
            $stakeholder_data = Stakeholder::find($inputs['stackholder']['stackholder_id']);
        }

        $stakeholder_data->site_id = decryptParams($site_id);
        $stakeholder_data->full_name = $inputs['stackholder']['full_name'];
        $stakeholder_data->father_name = $inputs['stackholder']['father_name'];
        $stakeholder_data->occupation = $inputs['stackholder']['occupation'];
        $stakeholder_data->designation = $inputs['stackholder']['designation'];
        $stakeholder_data->cnic = $inputs['stackholder']['cnic'];
        $stakeholder_data->contact = $inputs['stackholder']['contact'];
        $stakeholder_data->address = $inputs['stackholder']['address'];
        $stakeholder_data->save();

        $unit_id = Unit::where('floor_unit_number',$inputs['unit']['no'])->first()->id;
        $sales_plan_data = [
            'unit_id' => $unit_id,
            'stakeholder_id' => $stakeholder_data->id,
            'user_id' => Auth::user()->id,
            'unit_price' => $inputs['unit']['price']['unit'],
            'total_price' => $inputs['unit']['price']['total'],
            'discount_percentage' => $inputs['unit']['discount']['percentage'],
            'discount_total' => $inputs['unit']['discount']['total'],
            'down_payment_percentage' => $inputs['unit']['downpayment']['percentage'],
            'down_payment_total' => $inputs['unit']['downpayment']['total'],
            'sales_type' => $inputs['sales_source']['sales_type'],
            'indirect_source' => $inputs['sales_source']['indirect_source'],
            'validity' => $inputs['sales_plan_validity'],
            'status' => true,
        ];

        $sales_plan = $this->model()->create($sales_plan_data);

        foreach($inputs['unit']['additional_cost'] as $key => $value){
            $additonal_cost_id =  AdditionalCost::where('slug',$key)->first()->id;
            $sales_plan_additonal_cost= new SalesPlanAdditionalCost;
            $sales_plan_additonal_cost->sales_plan_id =  $sales_plan->id;
            $sales_plan_additonal_cost->additional_cost_id  =  $additonal_cost_id;
            $sales_plan_additonal_cost->percentage =  $value['percentage'];
            $sales_plan_additonal_cost->amount =  $value['total'];
            $sales_plan_additonal_cost->save();
        }

        foreach($inputs['installments']['table'] as $key => $table_data){
            $SalesPlanInstallments = new SalesPlanInstallments;
            $SalesPlanInstallments->sales_plan_id =  $sales_plan->id;
            $SalesPlanInstallments->date = Carbon::parse(str_replace('/', '-', $table_data['date']));
            $SalesPlanInstallments->details = $table_data['details'];
            $SalesPlanInstallments->amount = $table_data['amount'];
            $SalesPlanInstallments->remarks = $table_data['remarks'];
            $SalesPlanInstallments->save();

        }

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
                    'date' => $date->format('d/m/Y'),
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
                        'amount' => $inputs['installment_amount'],
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
