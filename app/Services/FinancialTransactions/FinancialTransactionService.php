<?php

namespace App\Services\FinancialTransactions;

use App\Exceptions\GeneralException;
use App\Models\{AccountAction, AccountHead, AccountingStartingCode, AccountLedger, FileBuyBack, FileCancellation, FileManagement, FileRefund, FileResale, FileTitleTransfer, Receipt, SalesPlan, Stakeholder, StakeholderType};
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use App\Utils\Enums\NatureOfAccountsEnum;
use Exception;
use Illuminate\Support\Facades\DB;
use Svg\Tag\Rect;

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


            $origin_number = AccountLedger::get();

            if (isset($origin_number) && count($origin_number) > 0) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
            } else {
                $origin_number = '001';
            }

            $accountUnitHeadCode = $this->findOrCreateUnitAccount($salesPlan->unit);
            $salesPlan->unit->refresh();

            $accountCustomerHeadCode = $this->findOrCreateCustomerAccount($salesPlan->unit->id, $accountUnitHeadCode, $salesPlan->stakeholder->stakeholder_types[0]);

            $this->makeFinancialTransaction($salesPlan->stakeholder->site->id, $origin_number, $accountCustomerHeadCode, 1, $salesPlan->id, 'debit', $salesPlan->total_price, NatureOfAccountsEnum::SALES_PLAN_APPROVAL);

            $revenueSales = (new AccountingStartingCode())->where('site_id', $salesPlan->stakeholder->site->id)
                ->where('model', 'App\Models\RevenueSales')->where('level', 5)->first();

            if (is_null($revenueSales)) {
                throw new GeneralException('Revenue Sales Account Head not found');
            }

            $revenueSalesAccount = $revenueSales->level_code . $revenueSales->starting_code;

            $this->makeFinancialTransaction($salesPlan->stakeholder->site->id,  $origin_number, $revenueSalesAccount, 1, $salesPlan->id, 'credit', $salesPlan->total_price, NatureOfAccountsEnum::SALES_PLAN_APPROVAL);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    public function makeDisapproveSalesPlanTransaction($sales_plan_id)
    {
        try {
            DB::beginTransaction();

            $sales_plan = SalesPlan::find($sales_plan_id);

            $origin_number = AccountLedger::where('account_action_id', 8)->get();

            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
            } else {
                $origin_number = '001';
            }

            // 1 disapproval sales plan entry
            $disapprovalAccount = AccountHead::where('name', 'Sales Plan Disapproval Account')->first()->code;
            $this->makeFinancialTransaction($sales_plan->stakeholder->site->id, $origin_number, $disapprovalAccount, 8, $sales_plan->id, 'debit', $sales_plan->total_price, NatureOfAccountsEnum::SALES_PLAN_DISAPPROVAL);

            // 2
            $customerAccount = collect($sales_plan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $sales_plan->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($sales_plan->stakeholder->site->id, $origin_number, $customerAccount['account_code'], 8, $sales_plan->id, 'credit', $sales_plan->total_price, NatureOfAccountsEnum::SALES_PLAN_DISAPPROVAL, null);


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

    public function makeFinancialTransaction($site_id, $origin_number,$account_code, $account_action, $sales_plan, $type, $amount, $nature_of_account, $action_id = null, $balance = 0)
    {
        $account_action_name = AccountAction::find($account_action)->name;

        $data = [
            'site_id' => $site_id,
            'account_head_code' => $account_code,
            'account_action_id' => $account_action,
            'sales_plan_id' => $sales_plan,
            'origin_number' => $origin_number,
            'balance' => $balance,
            'nature_of_account' => $nature_of_account,
            'status' => true,
        ];
        $data[$type] = $amount;
        $data['origin_name'] = $data['nature_of_account']->value . '-' . $origin_number;

        if($account_action == 1 || $account_action == 8){
            $sales_plan = SalesPlan::find($sales_plan);
            $data['created_date'] = $sales_plan->approved_date;
        }


        if ($account_action == 2 || $account_action == 9 || $account_action == 10 || $account_action == 11 || $account_action == 12) {
            $receipt = Receipt::find($action_id);
            $data['receipt_id'] = $action_id;
            $data['created_date'] = $receipt->created_date;
        }

        if ($account_action == 3) {
            $file_buy_back = FileBuyBack::find($action_id);
            $data['file_buyback_id'] = $action_id;
            $data['created_date'] = $file_buy_back->updated_at;
        }

        if ($account_action == 5) {
            $file_refund = FileRefund::find($action_id);
            $data['file_refund_id'] = $action_id;
            $data['created_date'] = $file_refund->updated_at;
        }

        if ($account_action == 6) {
            $file_cancellation = FileCancellation::find($action_id);
            $data['file_cancellation_id'] = $action_id;
            $data['created_date'] = $file_cancellation->updated_at;
        }

        if ($account_action == 7) {
            $file_title_transfer = FileTitleTransfer::find($action_id);
            $data['file_title_transfer_id'] = $action_id;
            $data['created_date'] = $file_title_transfer->updated_at;
        }

        if ($account_action == 24) {
            $file_resale = FileResale::find($action_id);
            $data['file_resale_id'] = $action_id;
            $data['created_date'] = $file_resale->updated_at;
        }

        return (new AccountLedger())->create($data);
    }

    // for cash
    public function makeReceiptTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new Receipt())->find($receipt_id);

            if(isset($receipt->discounted_amount)){
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            }
            else{
                $amount_in_numbers = $receipt->amount_in_numbers;
            }

            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
            } else {
                $origin_number = '001';
            }
            // Cash Transaction
            $cashAccount = (new AccountingStartingCode())->where('site_id', $receipt->site_id)
                ->where('model', 'App\Models\Cash')->where('level', 5)->first();

            if (is_null($cashAccount)) {
                throw new GeneralException('Cash Account is not defined. Please define cash account first.');
            }

            $cashAccount = $cashAccount->level_code . $cashAccount->starting_code;
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 2, $receipt->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if(isset($receipt->discounted_amount)){
                $cashDiscountAccount = AccountHead::where('name','Cash Discount')->where('level',5)->first()->code;

                // Discount Transaction
               $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 2, $receipt->sales_plan_id, 'debit', $receipt->discounted_amount, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);
            }

            // Customer AR Transaction
            $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }
            // $customerAccount = $customerAccount[0];
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 2, $receipt->sales_plan_id, 'credit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);


            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    // for cheque
    public function makeReceiptChequeTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new Receipt())->find($receipt_id);
            if(isset($receipt->discounted_amount)){
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            }
            else{
                $amount_in_numbers = $receipt->amount_in_numbers;
            }
            $clearanceAccout = AccountHead::where('name', 'Cheques Clearing Account')->first()->code;
            $stakeholder = Stakeholder::where('cnic', $receipt->cnic)->first();
            $stakeholderType = StakeholderType::where('stakeholder_id', $stakeholder->id)->where('type', 'C')->first();
            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
            } else {
                $origin_number = '001';
            }

            // Cheque Transaction
            $cashAccount = $clearanceAccout;
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 9, $receipt->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if(isset($receipt->discounted_amount)){
                $cashDiscountAccount = AccountHead::where('name','Cash Discount')->where('level',5)->first()->code;
                // Discount Transaction
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 9, $receipt->sales_plan_id, 'debit', $receipt->discounted_amount, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            }

            // Customer AR Transaction
            $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 9, $receipt->sales_plan_id, 'credit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);



            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    // in case of cheque active
    public function makeReceiptActiveTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new Receipt())->find($receipt_id);
            if(isset($receipt->discounted_amount)){
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            }
            else{
                $amount_in_numbers = $receipt->amount_in_numbers;
            }
            $bankAccount = $receipt->bank->account_number;
            $origin_number = AccountLedger::where('account_action_id', 9)->get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = (int)$origin_number->origin_number + 1;
            } else {
                $origin_number = '001';
            }
            // bank Transaction
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $bankAccount, 9, $receipt->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if(isset($receipt->discounted_amount)){
                $cashDiscountAccount = AccountHead::where('name','Cash Discount')->where('level',5)->first()->code;
                // Discount Transaction
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 9, $receipt->sales_plan_id, 'debit', $receipt->discounted_amount, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);
            }

            // Clearing account transaction
            $clearanceAccout = AccountHead::where('name', 'Cheques Clearing Account')->first()->code;

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $clearanceAccout, 9, $receipt->sales_plan_id, 'credit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    // in case of online
    public function makeReceiptOnlineTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new Receipt())->find($receipt_id);
            if(isset($receipt->discounted_amount)){
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            }
            else{
                $amount_in_numbers = $receipt->amount_in_numbers;
            }
            $bankAccount = $receipt->bank->account_number;
            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
            } else {
                $origin_number = '001';
            }
            // bank Transaction
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $bankAccount, 12, $receipt->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if(isset($receipt->discounted_amount)){
                $cashDiscountAccount = AccountHead::where('name','Cash Discount')->where('level',5)->first()->code;
                // Discount Transaction
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 12, $receipt->sales_plan_id, 'debit', $receipt->discounted_amount, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            }

            // Customer AR Transaction
            $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 12, $receipt->sales_plan_id, 'credit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

     // in case of receipt revert
     public function makeReceiptRevertTransaction($receipt_id)
     {
         try {
             DB::beginTransaction();

             $receipt = (new Receipt())->find($receipt_id);
             $bankAccount = $receipt->bank->account_number;
             $origin_number = AccountLedger::get();

             if (isset($origin_number)) {

                 $origin_number = collect($origin_number)->last();
                 $origin_number = $origin_number->origin_number + 1;
             } else {
                 $origin_number = '001';
             }
             // bank Transaction
             $this->makeFinancialTransaction($receipt->site_id, $origin_number, $bankAccount, 12, $receipt->sales_plan_id, 'credit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

             // Customer AR Transaction
             $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
             $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

             if (is_null($customerAccount)) {
                 throw new GeneralException('Customer Account is not defined. Please define customer account first.');
             }

             $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 12, $receipt->sales_plan_id, 'debit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

             DB::commit();
             return 'transaction_completed';
         } catch (GeneralException | Exception $ex) {
             DB::rollBack();
             return $ex;
         }
     }


    public function makeBuyBackTransaction($site_id, $unit_id, $customer_id, $file_id)
    {
        try {
            DB::beginTransaction();

            $file_buy_back = FileBuyBack::where('file_id', decryptParams($file_id))->first();
            $sales_plan = SalesPlan::find($file_buy_back->sales_plan_id);
            $receipt = Receipt::where('sales_plan_id', $file_buy_back->sales_plan_id)->first();
            $refunded_amount = (int)$file_buy_back->amount_to_be_refunded - (int)$file_buy_back->amount_profit;
            $payable_amount = (int)$sales_plan->total_price - (int)$refunded_amount;
            $refundWithProfit = (int)$file_buy_back->amount_to_be_refunded;
            $onlyProfitAmount = $file_buy_back->amount_profit;

            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
            } else {
                $origin_number = '001';
            }

            //1 Buyback account entry
            $buybackAccount = AccountHead::where('name', 'Buyback Account')->first()->code;
            $this->makeFinancialTransaction($file_buy_back->site_id, $origin_number, $buybackAccount, 3, $sales_plan->id, 'debit', $sales_plan->total_price, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);

            //2 Customer AR Transaction
            $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 3, $receipt->sales_plan_id, 'credit', $payable_amount, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);

            //3 customer payable transaction
            $stakeholderType = StakeholderType::where(['stakeholder_id' => decryptParams($customer_id), 'type' => 'C'])->first();
            $customer_payable_account_code = $stakeholderType->payable_account;

            //if payable account code is not set
            if ($customer_payable_account_code == null) {
                $stakeholderType = StakeholderType::where(['type' => 'C'])->where('payable_account', '!=', null)->get();
                $stakeholderType = collect($stakeholderType)->last();
                if ($stakeholderType == null) {
                    $customer_payable_account_code = '20201010001003';
                } else {
                    $customer_payable_account_code = $stakeholderType->payable_account + 1;
                }
                // add payable code to stakeholder type
                $stakeholderPayable = StakeholderType::where(['stakeholder_id' => decryptParams($customer_id), 'type' => 'C'])->first();
                $stakeholderPayable->payable_account =  (string)$customer_payable_account_code;
                $stakeholderPayable->update();

                $stakeholder = Stakeholder::find(decryptParams($customer_id));
                $accountCodeData = [
                    'site_id' => 1,
                    'modelable_id' => 1,
                    'modelable_type' => 'App\Models\StakeholderType',
                    'code' => $customer_payable_account_code,
                    'name' =>  $stakeholder->full_name . ' Customer A/P',
                    'level' => 5,
                ];

                (new AccountHead())->create($accountCodeData);
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 3, $receipt->sales_plan_id, 'credit', $refunded_amount, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);

            //4 Own profit entry
            $profitAccount = AccountHead::where('name', 'Customer Own Paid Expense')->first()->code;
            $this->makeFinancialTransaction($file_buy_back->site_id, $origin_number, $profitAccount, 3, $sales_plan->id, 'debit', $file_buy_back->amount_profit, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);

            //5 again customer ap entry for profit
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 3, $receipt->sales_plan_id, 'credit', $file_buy_back->amount_profit, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);

            //6 Payment Voucher  entry in customer ap ledger
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 4, $receipt->sales_plan_id, 'debit', $file_buy_back->amount_to_be_refunded, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);

            //7 Payment Voucher  cash entry
            $cashAccount = AccountHead::where('name', 'Cash at Office')->first()->code;
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 4, $receipt->sales_plan_id, 'credit', $file_buy_back->amount_to_be_refunded, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    public function makeFileCancellationTransaction($site_id, $unit_id, $customer_id, $file_id)
    {
        try {
            DB::beginTransaction();

            $file_cancellation = FileCancellation::where('file_id', decryptParams($file_id))->first();
            $sales_plan = SalesPlan::find($file_cancellation->sales_plan_id);
            $receipt = Receipt::where('sales_plan_id', $file_cancellation->sales_plan_id)->first();
            $refunded_amount = (int)$file_cancellation->amount_to_be_refunded + (int)$file_cancellation->cancellation_charges;
            $salesPlanRemainingAmount = (int)$sales_plan->total_price - (int)$refunded_amount;

            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
            } else {
                $origin_number = '001';
            }

            //1 Cancellation account entry
            $cancelationAccount = AccountHead::where('name', 'Cancellation Account')->first()->code;
            $this->makeFinancialTransaction($file_cancellation->site_id, $origin_number, $cancelationAccount, 6, $sales_plan->id, 'debit', $sales_plan->total_price, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);

            //2 Customer AR Transaction
            $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 6, $receipt->sales_plan_id, 'credit', $salesPlanRemainingAmount, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);

            //3 customer payable transaction
            $stakeholderType = StakeholderType::where(['stakeholder_id' => decryptParams($customer_id), 'type' => 'C'])->first();
            $customer_payable_account_code = $stakeholderType->payable_account;

            //if payable account code is not set
            if ($customer_payable_account_code == null) {
                $stakeholderType = StakeholderType::where(['type' => 'C'])->where('payable_account', '!=', null)->get();
                $stakeholderType = collect($stakeholderType)->last();
                if ($stakeholderType == null) {
                    $customer_payable_account_code = '20201010001003';
                } else {
                    $customer_payable_account_code = $stakeholderType->payable_account + 1;
                }
                // add payable code to stakeholder type
                $stakeholderPayable = StakeholderType::where(['stakeholder_id' => decryptParams($customer_id), 'type' => 'C'])->first();
                $stakeholderPayable->payable_account =  (string)$customer_payable_account_code;
                $stakeholderPayable->update();

                $stakeholder = Stakeholder::find(decryptParams($customer_id));
                $accountCodeData = [
                    'site_id' => 1,
                    'modelable_id' => 1,
                    'modelable_type' => 'App\Models\StakeholderType',
                    'code' => $customer_payable_account_code,
                    'name' =>  $stakeholder->full_name . ' Customer A/P',
                    'level' => 5,
                ];

                (new AccountHead())->create($accountCodeData);
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 6, $receipt->sales_plan_id, 'credit', $file_cancellation->amount_to_be_refunded, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);

            // 4 Revenue Canceleation Entry
            $revenueCancellationAccount = AccountHead::where('name', 'Revenue - Cancellation Charges')->first()->code;
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $revenueCancellationAccount, 6, $receipt->sales_plan_id, 'credit', $file_cancellation->cancellation_charges, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);

            // 5 Payment Voucher Customer A/P Entry
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 4, $receipt->sales_plan_id, 'debit', $refunded_amount, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);

            // 6 Payment Voucher Cash Entry
            $cashAccount = AccountHead::where('name', 'Cash at Office')->first()->code;
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 4, $receipt->sales_plan_id, 'credit', $refunded_amount, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    public function makeFileTitleTransferTransaction($site_id, $unit_id, $customer_id, $file_id)
    {
        try {
            DB::beginTransaction();

            $fileTitleTransfer = FileTitleTransfer::where('id', decryptParams($file_id))->first();
            $sales_plan = SalesPlan::find($fileTitleTransfer->sales_plan_id);
            $receipt = Receipt::where('sales_plan_id', $fileTitleTransfer->sales_plan_id)->first();
            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
            } else {
                $origin_number = '001';
            }
            //
            $stakeholderTypeB = StakeholderType::where(['stakeholder_id' => $fileTitleTransfer->transfer_person_id, 'type' => 'C'])->first();
            //
            $stakeholderTypeA = StakeholderType::where(['stakeholder_id' => decryptParams($customer_id), 'type' => 'C'])->first();

            $accountUnitHeadCode = $this->findOrCreateUnitAccount($sales_plan->unit);

            $customerBAccountRecievable = $this->findOrCreateCustomerAccount($unit_id, $accountUnitHeadCode, $stakeholderTypeB);

            $customerAAccountRecievable =  $this->findOrCreateCustomerAccount($unit_id, $accountUnitHeadCode, $stakeholderTypeA);

            $file = FileManagement::where('id', $fileTitleTransfer->file_id)->first();
            $receipts = Receipt::where('sales_plan_id', $file->sales_plan_id)->get();
            $total_paid_amount = $receipts->sum('amount_in_numbers');
            $remainingSalesPlanAmount = (int)$sales_plan->total_price -  (int)$total_paid_amount;

            // 1 Customer B AR entry
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerBAccountRecievable, 7, $receipt->sales_plan_id, 'debit', $remainingSalesPlanAmount, NatureOfAccountsEnum::JOURNAL_TITLE_TRANSFER, $fileTitleTransfer->id);

            // 2 Customer A AR entry

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAAccountRecievable, 7, $receipt->sales_plan_id, 'credit', $remainingSalesPlanAmount, NatureOfAccountsEnum::JOURNAL_TITLE_TRANSFER, $fileTitleTransfer->id);

            // 3 Customer A AR  Transfer charges entry
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAAccountRecievable, 7, $receipt->sales_plan_id, 'debit', $fileTitleTransfer->amount_to_be_paid, NatureOfAccountsEnum::JOURNAL_TITLE_TRANSFER, $fileTitleTransfer->id);

            // 4 Revenue transfer fee entry
            $transferAccount = AccountHead::where('name', 'Revenue - Transfer Fees')->first()->code;
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $transferAccount, 7, $receipt->sales_plan_id, 'credit', $fileTitleTransfer->amount_to_be_paid, NatureOfAccountsEnum::JOURNAL_TITLE_TRANSFER, $fileTitleTransfer->id);

            // 5 receipt voucher cash
            $cashAccount = AccountHead::where('name', 'Cash at Office')->first()->code;
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 2, $receipt->sales_plan_id, 'debit', $fileTitleTransfer->amount_to_be_paid, NatureOfAccountsEnum::JOURNAL_TITLE_TRANSFER, $fileTitleTransfer->id);

            // 6 customer a AR transfer fee entry
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAAccountRecievable, 2, $receipt->sales_plan_id, 'credit', $fileTitleTransfer->amount_to_be_paid, NatureOfAccountsEnum::JOURNAL_TITLE_TRANSFER, $fileTitleTransfer->id);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }
}
