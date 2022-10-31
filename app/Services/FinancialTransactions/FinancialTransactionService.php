<?php

namespace App\Services\FinancialTransactions;

use App\Exceptions\GeneralException;
use App\Models\{AccountHead, AccountingStartingCode, AccountLedger, Receipt, SalesPlan};
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use App\Utils\Enums\NatureOfAccountsEnum;
use Exception;
use Illuminate\Support\Facades\DB;

class FinancialTransactionService implements FinancialTransactionInterface
{
    public function makeSalesPlanTransaction($sales_plan_id)
    {
        try {
            DB::beginTransaction();

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
            // dd($salesPlan->stakeholder->site->id);
            $this->makeFinancialTransaction($salesPlan->stakeholder->site->id, $accountCustomerHeadCode, 1, $salesPlan->id, 'debit', $salesPlan->total_price, NatureOfAccountsEnum::SALES_PLAN_APPROVAL);

            $revenueSales = (new AccountingStartingCode())->where('site_id', $salesPlan->stakeholder->site->id)
                ->where('model', 'App\Models\RevenueSales')->where('level', 5)->first();

            if (is_null($revenueSales)) {
                throw new GeneralException('Revenue Sales Account Head not found');
            }

            $revenueSalesAccount = $revenueSales->level_code . $revenueSales->starting_code;

            $this->makeFinancialTransaction($salesPlan->stakeholder->site->id, $revenueSalesAccount, 1, $salesPlan->id, 'credit', $salesPlan->total_price, NatureOfAccountsEnum::SALES_PLAN_APPROVAL);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
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

        $oldUnitAccountAgainstType = collect($unit->unit_account)->where('type_id', $unitType->id)->where('type_account', $unitType->account_number)->first();
        // dd($oldUnitAccountAgainstType);
        if (!is_null($oldUnitAccountAgainstType)) {
            return (string)$oldUnitAccountAgainstType['account_code'];
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

        $this->saveAccountHead($unit->floor->site->id, $unit, $unit->floor_unit_number . ' Receviable', (string)$accountHead, 4);

        return (string)$accountHead;
    }

    private function findOrCreateCustomerAccount($unit_id, $accountUnitHeadCode, $stakeholderCustomerType)
    {
        // Get Customer Starting Code
        $accountingCustomerCode = (new AccountingStartingCode())->where([
            'level' => 5,
            'model' => 'App\Models\Stakeholder',
        ])->first();

        $oldCustomerAccountAgainstUnit = collect($stakeholderCustomerType->receivable_account)->where('unit_id', $unit_id)->where('unit_account', $accountUnitHeadCode)->first();
        // dd($oldCustomerAccountAgainstUnit);
        if (!is_null($oldCustomerAccountAgainstUnit)) {
            return (string)$oldCustomerAccountAgainstUnit['account_code'];
        }

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

        $this->saveAccountHead($stakeholderCustomerType->stakeholder->site->id, $stakeholderCustomerType, $stakeholderCustomerType->stakeholder->full_name . ' Customer A/R', (string)$accountHead, 5);

        return (string)$accountHead;
    }

    public function saveAccountHead($site_id, $model, $accountName, $accountCode, $level)
    {
        $model->modelable()->create([
            'site_id' => $site_id,
            'code' => $accountCode,
            'name' => $accountName,
            'level' => $level,
        ]);
        return true;
    }

    public function makeFinancialTransaction($site_id, $account_code, $account_action, $sales_plan, $type, $amount, $nature_of_account, $receipt_id = null, $balance = 0)
    {
        $data = [
            'site_id' => $site_id,
            'account_head_code' => $account_code,
            'account_action_id' => $account_action,
            'sales_plan_id' => $sales_plan,
            'receipt_id' => $receipt_id,
            'balance' => $balance,
            'nature_of_account' => $nature_of_account,
            'status' => true,
        ];

        $data[$type] = $amount;

        return (new AccountLedger())->create($data);
    }

    public function makeReceiptTransaction($receipt_id)
    {
        // try {
            // DB::beginTransaction();

            $receipt = (new Receipt())->find($receipt_id);

            // Cash Transaction
            $cashAccount = (new AccountingStartingCode())->where('site_id', $receipt->site_id)
                ->where('model', 'App\Models\Cash')->where('level', 5)->first();

            if (is_null($cashAccount)) {
                throw new GeneralException('Cash Account is not defined. Please define cash account first.');
            }

            $cashAccount = $cashAccount->level_code . $cashAccount->starting_code;
            $this->makeFinancialTransaction($receipt->site_id, $cashAccount, 2, $receipt->sales_plan_id, 'debit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // Customer AR Transaction
            $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }
            dd($customerAccount);
            $customerAccount = $customerAccount[0];
            $this->makeFinancialTransaction($receipt->site_id, $customerAccount['account_code'], 2, $receipt->sales_plan_id, 'credit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);
            // dd($customerAccount, $cashAccount, $receipt);

            // DB::commit();
            return 'transaction_completed';
        // } catch (GeneralException | Exception $ex) {
        //     DB::rollBack();
        //     return $ex;
        // }
    }
}
