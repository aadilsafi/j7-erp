<?php

namespace App\Services\AccountRecevories;

use App\Models\AdditionalCost;
use App\Models\SalesPlan;
use App\Services\AccountRecevories\AccountRecevoryInterface;
use Exception;
use Illuminate\Support\Str;

class AccountRecevoryService implements AccountRecevoryInterface
{

    public function generateDataTable($site_id)
    {

        $salesPlans = (new SalesPlan())->with(['unit', 'stakeholder', 'additionalCosts', 'installments', 'leadSource', 'receipts'])->where(['status' => 1])->get();

        $dataTable = collect($salesPlans)->transform(function ($salesPlan) use ($site_id) {
            $data['sales_plan'] = $salesPlan;
            $data['installments'] = array_reduce(collect($salesPlan->installments->where('type', 'installment'))->transform(function ($installment) {
                $installmentWithNewKeys = [];
                foreach ($installment->toArray() as $key => $value) {
                    $installmentWithNewKeys['installment_' . $installment->installment_order . '_' . $key] = $value;
                }
                return $installmentWithNewKeys;
            })->toArray(), 'array_merge', array());
            return $data;
        });

        return $dataTable;
    }
}
