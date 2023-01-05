<?php

namespace App\Services\AccountRecevories;

use App\Models\AdditionalCost;
use App\Models\SalesPlan;
use App\Services\AccountRecevories\AccountRecevoryInterface;
use Exception;
use Illuminate\Support\Str;

class AccountRecevoryService implements AccountRecevoryInterface
{

    public function generateDataTable($site_id, $filters = [])
    {
        $salesPlans = (new SalesPlan())->with(['unit', 'unit.floor', 'unit.type', 'stakeholder', 'stakeholder.dealer_stakeholder', 'additionalCosts', 'installments', 'leadSource', 'receipts'])->where(['status' => 1]);
        if (count($filters) > 0) {
            if (isset($filters['filter_floors']) && $filters['filter_floors'] > 0) {
                $salesPlans = $salesPlans->whereHas('unit.floor', function ($query) use ($filters) {
                    $query->where('floors.id', intval($filters['filter_floors']));
                });
            }

            if (isset($filters['filter_unit'])) {
                $salesPlans = $salesPlans->whereHas('unit', function ($query) use ($filters) {
                    $query->where('units.floor_unit_number', $filters['filter_unit']);
                });
            }

            if (isset($filters['filter_customer']) && $filters['filter_customer'] > 0) {
                $salesPlans = $salesPlans->where('sales_plans.stakeholder_id', $filters['filter_customer']);
            }

            if (isset($filters['filter_dealer']) && $filters['filter_dealer'] > 0) {
                $salesPlans = $salesPlans->whereHas('stakeholder.dealer_stakeholder', function ($query) use ($filters) {
                    $query->where('stakeholder_id', $filters['filter_dealer']);
                });
            }

            if (isset($filters['filter_sale_source']) && $filters['filter_sale_source'] > 0) {
                $salesPlans = $salesPlans->where('sales_plans.user_id', $filters['filter_sale_source']);
            }

            if (isset($filters['filter_type']) && $filters['filter_type'] > 0) {
                $salesPlans = $salesPlans->whereHas('unit.type', function ($query) use ($filters) {
                    $query->where('id', $filters['filter_type']);
                });
            }

            if (isset($filters['filter_generated_from']) && isset($filters['filter_generated_to']) ) {
                $salesPlans = $salesPlans->whereBetween('sales_plans.created_at', [$filters['filter_generated_from'], $filters['filter_generated_to']]);
            }

            if (isset($filters['filter_approveed_from']) && isset($filters['filter_approveed_to']) ) {
                $salesPlans = $salesPlans->whereBetween('sales_plans.approved_date', [$filters['filter_generated_from'], $filters['filter_generated_to']]);
            }
        }

        $salesPlans = $salesPlans->get();

        $dataTable = collect($salesPlans)->transform(function ($salesPlan) use ($site_id) {
            $data['sales_plan'] = $salesPlan;
            $data['installments'] = array_reduce(collect($salesPlan->installments->where('type', 'installment'))->transform(function ($installment) {
                $installmentWithNewKeys = [];
                foreach ($installment->toArray() as $key => $value) {
                    $installmentWithNewKeys['installment_' . $installment->installment_order . '_' . $key] = $value;
                }
                return $installmentWithNewKeys;
            })->toArray(), 'array_merge', array());
            dd($data['installments']);
            return $data;
        });
        return $dataTable;
    }
}
