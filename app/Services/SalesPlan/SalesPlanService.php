<?php

namespace App\Services\SalesPlan;

use App\Models\{Floor, SalesPlan, Site, Unit};
use App\Services\SalesPlan\Interface\SalesPlanInterface;
use Carbon\{Carbon, CarbonPeriod};
use Illuminate\Support\LazyCollection;

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
    // public function store($site_id, $inputs)
    // {
    //     $data = [
    //         'site_id' => decryptParams($site_id),
    //         'name' => filter_strip_tags($inputs['name']),
    //         'short_label' => Str::of(filter_strip_tags($inputs['short_label']))->upper(),
    //         'floor_area' => filter_strip_tags($inputs['floor_area']),
    //         'order' => filter_strip_tags($inputs['floor_order']),
    //         'active' => true,
    //     ];

    //     $floor = $this->model()->create($data);
    //     return $floor;
    // }

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
        $start = microtime(true);

        $installments = [
            'site' => (new Site())->find(decryptParams($site_id)),
            'floor' => (new Floor())->find(decryptParams($floor_id)),
            'unit' => (new Unit())->find(decryptParams($unit_id)),
        ];

        $unchangedData = isset($inputs['unchangedData']) ? $inputs['unchangedData'] : [];

        $installmentDates = $this->dateRanges($inputs['startDate'], $inputs['length'], $inputs['rangeCount'], $inputs['rangeBy']);

        $amount = $this->baseInstallment($inputs['installment_amount'], $inputs['length']);

        $installments['installments'] = collect($installmentDates)->map(function ($date, $key) use (&$amount, $unchangedData) {

            $filteredData = LazyCollection::make($unchangedData)->where('key', $key + 1)->whereIn('field', ['details', 'amount', 'remarks'])->map(function ($item) {
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
            ];

            $installmentRow['row'] = view('app.sites.floors.units.sales-plan.partials.installment-table-row', $rowData)->render();

            return $installmentRow;
        })->toArray();

        $installments['installments'][] = [
            'row' => $installmentRow['row'] = view('app.sites.floors.units.sales-plan.partials.installment-table-row', [
                'key' => '',
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
            ])->render(),
        ];

        $time_elapsed_secs = microtime(true) - $start;

        return $installments;
    }


    private function baseInstallment($total, $divide)
    {
        return round($total / $divide);
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
