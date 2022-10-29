<?php

namespace App\Services\FinancialTransactions;

use App\Exceptions\GeneralException;
use App\Models\{AccountHead, AccountingStartingCode, SalesPlan};
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use Exception;
use Illuminate\Support\Facades\DB;

class FinancialTransactionService implements FinancialTransactionInterface
{
    public function makeSalesPlanTransaction($sales_plan_id)
    {
        try {
            // DB::beginTransaction();

            $salesPlan = (new SalesPlan())->with([
                'unit',
                'unit.type',
                'user',
                'stakeholder',
                'stakeholder.stakeholder_types' => function ($query) {
                    $query->where('type', 'C');
                },
                'additionalCosts'
            ])->find($sales_plan_id);


            $accountUnitHeadCode = $this->findOrCreateUnitAccount($salesPlan->unit);
            $salesPlan->unit->refresh();

            $accountCustomerHeadCode = $this->findOrCreateCustomerAccount($salesPlan->unit->id, $accountUnitHeadCode, $salesPlan->stakeholder->stakeholder_types[0]);

            dd($accountUnitHeadCode, $salesPlan->unit);

            // DB::commit();

            dd('done');
        } catch (GeneralException | Exception $ex) {
            // DB::rollBack();
            return $ex;
        }
    }

    private function findOrCreateUnitAccount($unit)
    {
        // Get Unit Starting Code
        $accountingUnitCode = (new AccountingStartingCode())->where([
            'level' => 4,
            'model' => 'App\Models\Unit',
        ])->first();


        $unitType = $unit->type;
        if ($unitType->parent_id > 0) {
            $unitType = getTypeAncesstorData($unitType->parent_id);
        }

        if (!$unitType->account_added) {
            throw new GeneralException('Unit type account not added');
        }


        $AccountNumber = $unitType->account_number;
        $StartingCode = $accountingUnitCode->starting_code;
        $level = 4;
        $EndingCode = 9999;

        $startNumber = ($AccountNumber) . $accountingUnitCode->starting_code;
        $endNumber = $AccountNumber . $EndingCode;

        $accountHead = (new AccountHead())->where('level', $level)->whereBetween('code', [$startNumber, $endNumber])->orderBy('code')->get();

        if (count($accountHead) > 0) {

            $accountHead = collect($accountHead)->last();
            $code = $accountHead->code + 1;

            if ($code > intval($endNumber)) {
                throw new GeneralException('Accounts are conflicting. Please rearrange your coding system.');
            } else {
                $accountHead = $code;
            }
        } else {
            $accountHead = $AccountNumber . $StartingCode;
        }


        // Get and Save Unit Code
        $ancesstorType = getTypeAncesstorData($unit->type->id);
        $arrUnitAccount = $unit->unit_account;
        $arrUnitAccount[] = [
            "type_id" => $ancesstorType->id,
            "type_account" => $ancesstorType->account_number,
            "account_code" => $accountHead,
            "default" => true,
            "active" => true
        ];
        $unit->unit_account = $arrUnitAccount;
        $unit->save();

        return (string)$accountHead;
    }

    private function findOrCreateCustomerAccount($unit_id, $accountUnitHeadCode, $stakeholderCustomerType)
    {
        // Get Customer Starting Code
        $accountingCustomerCode = (new AccountingStartingCode())->where([
            'level' => 5,
            'model' => 'App\Models\Stakeholder',
        ])->first();

        $AccountNumber = $accountUnitHeadCode;
        $StartingCode = $accountingCustomerCode->starting_code;
        $level = 5;
        $EndingCode = 9999;

        $startNumber = ($AccountNumber) . $StartingCode;
        $endNumber = $AccountNumber . $EndingCode;

        $accountHead = (new AccountHead())->where('level', $level)->whereBetween('code', [$startNumber, $endNumber])->orderBy('code')->get();

        if (count($accountHead) > 0) {

            $accountHead = collect($accountHead)->last();
            $code = $accountHead->code + 1;

            if ($code > intval($endNumber)) {
                throw new GeneralException('Accounts are conflicting. Please rearrange your coding system.');
            } else {
                $accountHead = $code;
            }
        } else {
            $accountHead = $AccountNumber . $StartingCode;
        }

        // Get and Save Customer Code
        $arrStakeholderAccount = $stakeholderCustomerType->receivable_account;
        $arrStakeholderAccount[] = [
            "unit_id" => $unit_id,
            "unit_account" => $accountUnitHeadCode,
            "account_code" => $accountHead,
            "default" => true,
            "active" => true
        ];
        $stakeholderCustomerType->receivable_account = $arrStakeholderAccount;
        $stakeholderCustomerType->save();

        return (string)$accountHead;
    }

    public function saveAccountHead($model, $accountCode, $level){

    }
}
