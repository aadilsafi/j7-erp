<?php

namespace App\Services\FinancialTransactions;

use App\Exceptions\GeneralException;
use App\Models\{AccountAction, AccountHead, AccountingStartingCode, AccountLedger, Bank, DealerIncentiveModel, FileBuyBack, FileCancellation, FileManagement, FileRefund, FileResale, FileTitleTransfer, InvsetorDealsReceipt, PaymentVocuher, RebateIncentiveModel, Receipt, SalesPlan, SalesPlanInstallments, Stakeholder, StakeholderInvestor, StakeholderType, TransferReceipt};
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use App\Utils\Enums\NatureOfAccountsEnum;
use Auth;
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

            $expense = SalesPlanInstallments::where(['sales_plan_id' => $salesPlan->id, 'type' => 'additional_expense'])->get();

            if (isset($expense) && count($expense) > 0) {
                $expense_amount = collect($expense)->sum('amount');
                $sales_plan_total = (float)$expense_amount + (float)$salesPlan->total_price;
            } else {
                $sales_plan_total = $salesPlan->total_price;
            }
            $origin_number = AccountLedger::get();



            if (isset($origin_number) && count($origin_number) > 0) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }

            $accountUnitHeadCode = $this->findOrCreateUnitAccount($salesPlan->unit);
            $salesPlan->unit->refresh();

            $accountCustomerHeadCode = $this->findOrCreateCustomerAccount($salesPlan->unit->id, $accountUnitHeadCode, $salesPlan->stakeholder->stakeholder_types[0]);

            $this->makeFinancialTransaction($salesPlan->stakeholder->site->id, $origin_number, $accountCustomerHeadCode, 1, $salesPlan->id, 'debit', $sales_plan_total, NatureOfAccountsEnum::SALES_PLAN_APPROVAL);

            $revenueSales = (new AccountingStartingCode())->where('site_id', $salesPlan->stakeholder->site->id)
                ->where('model', 'App\Models\RevenueSales')->where('level', 5)->first();

            if (is_null($revenueSales)) {
                throw new GeneralException('Revenue Sales Account Head not found');
            }

            $revenueSalesAccount = $revenueSales->level_code . $revenueSales->starting_code;

            $this->makeFinancialTransaction($salesPlan->stakeholder->site->id,  $origin_number, $revenueSalesAccount, 1, $salesPlan->id, 'credit', $sales_plan_total, NatureOfAccountsEnum::SALES_PLAN_APPROVAL);

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

            $expense = SalesPlanInstallments::where(['sales_plan_id' => $sales_plan->id, 'type' => 'additional_expense'])->get();

            if (isset($expense) && count($expense) > 0) {
                $expense_amount = collect($expense)->sum('amount');
                $sales_plan_total = (float)$expense_amount + (float)$sales_plan->total_price;
            } else {
                $sales_plan_total = $sales_plan->total_price;
            }

            $origin_number = AccountLedger::where('account_action_id', 8)->get();

            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }

            // 1 disapproval sales plan entry
            $disapprovalAccount = AccountHead::where('name', 'Sales Plan Disapproval Account')->first()->code;
            $this->makeFinancialTransaction($sales_plan->stakeholder->site->id, $origin_number, $disapprovalAccount, 8, $sales_plan->id, 'debit', $sales_plan_total, NatureOfAccountsEnum::SALES_PLAN_DISAPPROVAL);

            // 2
            $customerAccount = collect($sales_plan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $sales_plan->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($sales_plan->stakeholder->site->id, $origin_number, $customerAccount['account_code'], 8, $sales_plan->id, 'credit', $sales_plan_total, NatureOfAccountsEnum::SALES_PLAN_DISAPPROVAL, null);


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
        $account_type = 'debit';

        $this->saveAccountHead($unit->floor->site->id, $unit, $unit->floor_unit_number . ' Receivable', (string)$accountHead, 4, $account_type);

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
        $account_type = 'debit';

        $this->saveAccountHead($stakeholderCustomerType->stakeholder->site->id, $stakeholderCustomerType, $stakeholderCustomerType->stakeholder->full_name . ' Customer A/R', (string)$accountHead, 5, $account_type);

        return (string)$accountHead;
    }

    public function saveAccountHead($site_id, $model, $accountName, $accountCode, $level, $account_type)
    {
        $model->modelable()->create([
            'site_id' => $site_id,
            'code' => $accountCode,
            'name' => $accountName,
            'level' => $level,
            'account_type' => $account_type,
        ]);
        return true;
    }

    public function makeFinancialTransaction($site_id, $origin_number, $account_code, $account_action, $sales_plan, $type, $amount, $nature_of_account, $action_id = null, $balance = 0)
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

        if ($account_action == 1 || $account_action == 8) {
            $saalesPlan = SalesPlan::find($sales_plan);

            $data['origin_name'] = $saalesPlan->payment_plan_serial_id;
        } else {
            $data['origin_name'] = $data['nature_of_account']->value . '-' . sprintf('%03d', $action_id);
        }

        if ($account_action == 1) {
            $sales_plan = SalesPlan::find($sales_plan);
            $data['created_date'] = $sales_plan->approved_date;
        }

        if ($account_action == 8) {
            $sales_plan = SalesPlan::find($sales_plan);
            $data['created_date'] = now();
        }

        if ($account_action == 2 || $account_action == 9 || $account_action == 10 || $account_action == 11 || $account_action == 12 || $account_action == 27 || $account_action == 29) {
            $receipt = Receipt::find($action_id);
            $data['receipt_id'] = $action_id;
            $data['created_date'] = $receipt->created_date;
        }

        if ($account_action == 3) {
            $file_buy_back = FileBuyBack::find($action_id);
            $data['file_buyback_id'] = $action_id;
            $data['created_date'] = $file_buy_back->payment_due_date;
        }

        if ($account_action == 4) {
            $payment_voucher = PaymentVocuher::find($action_id);
            $data['payment_voucher_id'] = $action_id;
        }

        if ($account_action == 5) {
            $file_refund = FileRefund::find($action_id);
            $data['file_refund_id'] = $action_id;
            $data['created_date'] = $file_refund->payment_due_date;
        }

        if ($account_action == 6) {
            $file_cancellation = FileCancellation::find($action_id);
            $data['file_cancellation_id'] = $action_id;
            $data['created_date'] = $file_cancellation->payment_due_date;
        }

        if ($account_action == 7) {
            $file_title_transfer = FileTitleTransfer::find($action_id);
            $data['file_title_transfer_id'] = $action_id;
            $data['created_date'] = $file_title_transfer->payment_due_date;
        }

        if ($account_action == 24) {
            $file_resale = FileResale::find($action_id);
            $data['file_resale_id'] = $action_id;
            $data['created_date'] = $file_resale->payment_due_date;
        }

        if ($account_action == 25) {
            $rebate = RebateIncentiveModel::find($action_id);
            $data['rebate_incentive_id'] = $action_id;
            $data['created_date'] = now();
        }

        if ($account_action == 26) {
            $dealer = DealerIncentiveModel::find($action_id);
            $data['dealer_incentive_id'] = $action_id;
            $data['created_date'] = now();
        }

        if ($account_action == 30 || $account_action == 32 || $account_action == 33 || $account_action == 34 || $account_action == 31 || $account_action == 35) {
            $receipt = TransferReceipt::find($action_id);
            $data['transfer_receipt_id'] = $action_id;
            $data['created_date'] = $receipt->created_date;
        }

        if ($account_action == 26) {
            $dealer = DealerIncentiveModel::find($action_id);
            $data['dealer_incentive_id'] = $action_id;
            $data['created_date'] = now();
        }

        if ($account_action == 36) {
            $data['journal_voucher_id'] = $action_id;
        }

        if ($account_action == 38) {
            $data['investor_deal_id'] = $action_id;
        }

        if ($account_action == 39) {
            $data['investor_deal_receipt_id'] = $action_id;
        }

        return (new AccountLedger())->create($data);
    }

    // for cash
    public function makeReceiptTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new Receipt())->find($receipt_id);

            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            } else {
                $amount_in_numbers = $receipt->amount_in_numbers;
            }

            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
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
            $test = $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 2, $receipt->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;

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
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            } else {
                $amount_in_numbers = $receipt->amount_in_numbers;
            }
            $clearanceAccout = AccountHead::where('name', 'Cheques Clearing Account')->first()->code;
            $stakeholder = Stakeholder::where('cnic', $receipt->cnic)->first();
            $stakeholderType = StakeholderType::where('stakeholder_id', $stakeholder->id)->where('type', 'C')->first();
            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }

            // Cheque Transaction
            $cashAccount = $clearanceAccout;
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 9, $receipt->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;
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
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            } else {
                $amount_in_numbers = $receipt->amount_in_numbers;
            }
            $bankAccount = $receipt->bank->account_head_code;
            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }
            // bank Transaction
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $bankAccount, 9, $receipt->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;
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
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            } else {
                $amount_in_numbers = $receipt->amount_in_numbers;
            }
            $bankAccount = $receipt->bank->account_head_code;
            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }
            // bank Transaction
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $bankAccount, 12, $receipt->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;
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


    // in case of other payment mode
    public function makeReceiptOtherTransaction($receipt_id)
    {

        $origin_number = AccountLedger::get();
        if (isset($origin_number)) {
            $origin_number = collect($origin_number)->last();
            $origin_number = $origin_number->origin_number + 1;
            $origin_number =  sprintf('%03d', $origin_number);
        } else {
            $origin_number = '001';
        }

        $receipt = (new Receipt())->find($receipt_id);

        if (isset($receipt->customer_ap_amount) && (float)$receipt->customer_ap_amount > 0) {

            $customerPayableAccount = StakeholderType::where('stakeholder_id', $receipt->salesPlan->stakeholder_id)->where('type', 'C')->first()->payable_account;

            if (is_null($customerPayableAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerPayableAccount, 29, $receipt->sales_plan_id, 'debit', $receipt->customer_ap_amount, NatureOfAccountsEnum::Customer_AP_Account, $receipt->id);
        }

        if (isset($receipt->dealer_ap_amount) && (float)$receipt->dealer_ap_amount) {

            $dealerPayableAccount = StakeholderType::where('stakeholder_id', $receipt->salesPlan->stakeholder_id)->where('type', 'D')->first()->payable_account;


            if (is_null($dealerPayableAccount)) {
                throw new GeneralException('Dealer Account is not defined. Please define dealer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $dealerPayableAccount, 29, $receipt->sales_plan_id, 'debit', $receipt->dealer_ap_amount, NatureOfAccountsEnum::Dealer_AP_Account, $receipt->id);
        }

        if (isset($receipt->vendor_ap_amount) && (float)$receipt->vendor_ap_amount) {

            $vendorPayableAccount = StakeholderType::where('stakeholder_id', $receipt->salesPlan->stakeholder_id)->where('type', 'V')->first()->payable_account;

            if (is_null($vendorPayableAccount)) {
                throw new GeneralException('Vendor Account is not defined. Please define vendor account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $vendorPayableAccount, 29, $receipt->sales_plan_id, 'debit', $receipt->vendor_ap_amount, NatureOfAccountsEnum::Vendor_AP_Account, $receipt->id);
        }

        // if disocunt amount availaibe
        if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
            $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;
            // Discount Transaction
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 12, $receipt->sales_plan_id, 'debit', $receipt->discounted_amount, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);
        }


        // Customer AR Transaction
        $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
        $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

        if (is_null($customerAccount)) {
            throw new GeneralException('Customer Account is not defined. Please define customer account first.');
        }

        $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 29, $receipt->sales_plan_id, 'credit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);
    }

    // in case of cash receipt revert
    public function makeReceiptRevertCashTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new Receipt())->find($receipt_id);

            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            } else {
                $amount_in_numbers = $receipt->amount_in_numbers;
            }

            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
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
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 27, $receipt->sales_plan_id, 'credit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;

                // Discount Transaction
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 27, $receipt->sales_plan_id, 'credit', $receipt->discounted_amount, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);
            }

            // Customer AR Transaction
            $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }
            // $customerAccount = $customerAccount[0];
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 27, $receipt->sales_plan_id, 'debit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);


            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    // in case of cheque receipt revert
    public function makeReceiptRevertChequeTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new Receipt())->find($receipt_id);

            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            } else {
                $amount_in_numbers = $receipt->amount_in_numbers;
            }

            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
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
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 27, $receipt->sales_plan_id, 'credit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;

                // Discount Transaction
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 27, $receipt->sales_plan_id, 'credit', $receipt->discounted_amount, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);
            }

            // Customer AR Transaction
            $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }
            // $customerAccount = $customerAccount[0];
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 27, $receipt->sales_plan_id, 'debit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);


            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    // in case of online receipt revert
    public function makeReceiptRevertOnlineTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new Receipt())->find($receipt_id);
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $amount_in_numbers = (float)$receipt->amount_in_numbers - (float)$receipt->discounted_amount;
                $amount_in_numbers = (string)$amount_in_numbers;
            } else {
                $amount_in_numbers = $receipt->amount_in_numbers;
            }
            $bankAccount = $receipt->bank->account_head_code;
            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }
            // bank Transaction
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $bankAccount, 27, $receipt->sales_plan_id, 'credit', $amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

            // if disocunt amount availaibe
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;
                // Discount Transaction
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 27, $receipt->sales_plan_id, 'credit', $receipt->discounted_amount, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);
            }

            // Customer AR Transaction
            $customerAccount = collect($receipt->salesPlan->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 27, $receipt->sales_plan_id, 'debit', $receipt->amount_in_numbers, NatureOfAccountsEnum::RECEIPT_VOUCHER, $receipt->id);

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


            $receiptDiscounted = Receipt::where('sales_plan_id', $file_buy_back->sales_plan_id)->where('status', 1)->where('discounted_amount', '>', 0)->get();
            $discounted_amount = collect($receiptDiscounted)->sum('discounted_amount');
            $discountedValue = (float)$discounted_amount;


            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
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


            // if discount available

            if (isset($discounted_amount) && $discountedValue > 0) {
                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;
                // Discount Transaction
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 3, $receipt->sales_plan_id, 'credit', $discounted_amount, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);
            }

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
                    'account_type' => 'debit',
                ];

                (new AccountHead())->create($accountCodeData);
            }

            $discountedValue = (float)$discounted_amount;

            if (isset($discounted_amount) && $discountedValue > 0) {
                $amount = (float)$refunded_amount - (float)$discounted_amount;
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 3, $receipt->sales_plan_id, 'credit', $amount, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);
            } else {
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 3, $receipt->sales_plan_id, 'credit', $refunded_amount, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);
            }


            //4 Own profit entry
            $profitAccount = AccountHead::where('name', 'Customer Own Paid Expense')->first()->code;
            $this->makeFinancialTransaction($file_buy_back->site_id, $origin_number, $profitAccount, 3, $sales_plan->id, 'debit', $file_buy_back->amount_profit, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);

            //5 again customer ap entry for profit
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 3, $receipt->sales_plan_id, 'credit', $file_buy_back->amount_profit, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);

            // //6 Payment Voucher  entry in customer ap ledger
            // if (isset($discounted_amount) && $discountedValue > 0) {
            //     $amount = (float)$file_buy_back->amount_to_be_refunded - (float)$discounted_amount;
            //     $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 4, $receipt->sales_plan_id, 'debit', $amount, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);
            // } else {
            //     $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 4, $receipt->sales_plan_id, 'debit', $file_buy_back->amount_to_be_refunded, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);
            // }
            // //7 Payment Voucher  cash entry
            // $cashAccount = AccountHead::where('name', 'Cash at Office')->first()->code;
            // if (isset($discounted_amount) && $discountedValue > 0) {
            //     $amount = (float)$file_buy_back->amount_to_be_refunded - (float)$discounted_amount;
            //     $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 4, $receipt->sales_plan_id, 'credit',  $amount, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);
            // } else {
            //     $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 4, $receipt->sales_plan_id, 'credit', $file_buy_back->amount_to_be_refunded, NatureOfAccountsEnum::JOURNAL_BUY_BACK, $file_buy_back->id);
            // }
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


            $receiptDiscounted = Receipt::where('sales_plan_id', $file_cancellation->sales_plan_id)->where('status', 1)->where('discounted_amount', '>', 0)->get();
            $discounted_amount = collect($receiptDiscounted)->sum('discounted_amount');
            $discountedValue = (float)$discounted_amount;

            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
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

            // if discount available

            if (isset($discounted_amount) && $discountedValue > 0) {
                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;
                // Discount Transaction
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 6, $receipt->sales_plan_id, 'credit', $discounted_amount, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);
            }


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
                    'account_type' => 'debit',
                ];

                (new AccountHead())->create($accountCodeData);
            }

            if (isset($discounted_amount) && $discountedValue > 0) {
                $amount = (float)$file_cancellation->amount_to_be_refunded - (float)$discounted_amount;
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 6, $receipt->sales_plan_id, 'credit', $amount, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);
            } else {
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 6, $receipt->sales_plan_id, 'credit', $file_cancellation->amount_to_be_refunded, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);
            }
            // 4 Revenue Canceleation Entry
            $revenueCancellationAccount = AccountHead::where('name', 'Revenue - Cancellation Charges')->first()->code;
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $revenueCancellationAccount, 6, $receipt->sales_plan_id, 'credit', $file_cancellation->cancellation_charges, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);

            // // 5 Payment Voucher Customer A/P Entry
            // if (isset($discounted_amount) && $discountedValue > 0) {
            //     $amount = (float)$refunded_amount - (float)$discounted_amount;
            //     $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 4, $receipt->sales_plan_id, 'debit', $amount, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);
            // } else {
            //     $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customer_payable_account_code, 4, $receipt->sales_plan_id, 'debit', $refunded_amount, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);
            // }
            // // 6 Payment Voucher Cash Entry
            // $cashAccount = AccountHead::where('name', 'Cash at Office')->first()->code;
            // if (isset($discounted_amount) && $discountedValue > 0) {
            //     $amount = (float)$refunded_amount - (float)$discounted_amount;
            //     $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 4, $receipt->sales_plan_id, 'credit', $amount, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);
            // } else {
            //     $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 4, $receipt->sales_plan_id, 'credit', $refunded_amount, NatureOfAccountsEnum::JOURNAL_CANCELLATION, $file_cancellation->id);
            // }
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
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }
            //
            $stakeholderTypeB = StakeholderType::where(['stakeholder_id' => $fileTitleTransfer->transfer_person_id, 'type' => 'C'])->first();
            //
            $stakeholderTypeA = StakeholderType::where(['stakeholder_id' => decryptParams($customer_id), 'type' => 'C'])->first();

            $accountUnitHeadCode = $this->findOrCreateUnitAccount($sales_plan->unit);

            $customerBAccountRecievable = $this->findOrCreateCustomerAccount($sales_plan->unit->id, $accountUnitHeadCode, $stakeholderTypeB);

            $customerAAccountRecievable =  $this->findOrCreateCustomerAccount($sales_plan->unit->id, $accountUnitHeadCode, $stakeholderTypeA);

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

            // // 5 receipt voucher cash
            // $cashAccount = AccountHead::where('name', 'Cash at Office')->first()->code;
            // $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 28, $receipt->sales_plan_id, 'debit', $fileTitleTransfer->amount_to_be_paid, NatureOfAccountsEnum::JOURNAL_TITLE_TRANSFER, $fileTitleTransfer->id);

            // // 6 customer a AR transfer fee entry
            // $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAAccountRecievable, 28, $receipt->sales_plan_id, 'credit', $fileTitleTransfer->amount_to_be_paid, NatureOfAccountsEnum::JOURNAL_TITLE_TRANSFER, $fileTitleTransfer->id);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    public function makeFileResaleTransaction($site_id, $unit_id, $customer_id, $file_id)
    {
    }

    public function makeRebateIncentiveTransaction($rebate_id)
    {

        $rebate = RebateIncentiveModel::find($rebate_id);

        $origin_number = AccountLedger::get();
        if (isset($origin_number)) {
            $origin_number = collect($origin_number)->last();
            $origin_number = $origin_number->origin_number + 1;
            $origin_number =  sprintf('%03d', $origin_number);
        } else {
            $origin_number = '001';
        }

        $rebateExpanseAccount = AccountHead::where('name', 'Dealer Rebate Expense')->first()->code;

        # Rebate Expanse Entry
        $this->makeFinancialTransaction($rebate->site_id, $origin_number, $rebateExpanseAccount, 25, null, 'debit', $rebate->commision_total, NatureOfAccountsEnum::Rebate_Incentive, $rebate->id);

        $dealer =  Stakeholder::find($rebate->dealer_id);

        $stakeholderType = StakeholderType::where(['stakeholder_id' => $rebate->dealer_id, 'type' => 'D'])->first();

        if (isset($stakeholderType->payable_account)) {
            $dealer_payable_account_code = $stakeholderType->payable_account;
        } else {
            $dealer_payable_account_code = null;
        }
        //if payable account code is not set
        if ($dealer_payable_account_code == null) {
            $stakeholderType = StakeholderType::where(['type' => 'D'])->where('payable_account', '!=', null)->get();
            $stakeholderType = collect($stakeholderType)->last();
            if ($stakeholderType == null) {
                $dealer_payable_account_code = '20201020000001';
            } else {
                $dealer_payable_account_code = $stakeholderType->payable_account + 1;
            }
            // add payable code to stakeholder type
            $stakeholderPayable = StakeholderType::where(['stakeholder_id' => $rebate->dealer_id, 'type' => 'D'])->first();
            $stakeholderPayable->payable_account =  (string)$dealer_payable_account_code;
            $stakeholderPayable->update();

            $stakeholder = Stakeholder::find($rebate->dealer_id);
            $accountCodeData = [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => $dealer_payable_account_code,
                'name' =>  $stakeholder->full_name . ' Dealer A/P',
                'level' => 5,
                'account_type' => 'credit',
            ];

            (new AccountHead())->create($accountCodeData);
        }

        // Dealer AP account entry Debit
        $this->makeFinancialTransaction($rebate->site_id, $origin_number, $dealer_payable_account_code, 25, null, 'credit', $rebate->commision_total, NatureOfAccountsEnum::Rebate_Incentive, $rebate->id);
    }

    public function makeDealerIncentiveTransaction($dealer_incentive_id)
    {
        $dealer_incentive = DealerIncentiveModel::find($dealer_incentive_id);

        $origin_number = AccountLedger::get();
        if (isset($origin_number)) {
            $origin_number = collect($origin_number)->last();
            $origin_number = $origin_number->origin_number + 1;
            $origin_number =  sprintf('%03d', $origin_number);
        } else {
            $origin_number = '001';
        }

        // Dealer Incentive Expense Account
        $dealerExpanseAccount = AccountHead::where('name', 'Dealer Incentive Expense')->first()->code;

        # Dealer Expanse Entry
        $this->makeFinancialTransaction($dealer_incentive->site_id, $origin_number, $dealerExpanseAccount, 26, null, 'debit', $dealer_incentive->total_dealer_incentive, NatureOfAccountsEnum::Dealer_Incentive, $dealer_incentive->id);


        $dealer =  Stakeholder::find($dealer_incentive->dealer_id);

        $stakeholderType = StakeholderType::where(['stakeholder_id' => $dealer_incentive->dealer_id, 'type' => 'D'])->first();

        if (isset($stakeholderType->payable_account)) {
            $dealer_payable_account_code = $stakeholderType->payable_account;
        } else {
            $dealer_payable_account_code = null;
        }
        //if payable account code is not set
        if ($dealer_payable_account_code == null) {
            $stakeholderType = StakeholderType::where(['type' => 'D'])->where('payable_account', '!=', null)->get();
            $stakeholderType = collect($stakeholderType)->last();
            if ($stakeholderType == null) {
                $dealer_payable_account_code = '20201020000001';
            } else {
                $dealer_payable_account_code = $stakeholderType->payable_account + 1;
            }
            // add payable code to stakeholder type
            $stakeholderPayable = StakeholderType::where(['stakeholder_id' => $dealer_incentive->dealer_id, 'type' => 'D'])->first();
            $stakeholderPayable->payable_account =  (string)$dealer_payable_account_code;
            $stakeholderPayable->update();

            $stakeholder = Stakeholder::find($dealer_incentive->dealer_id);
            $accountCodeData = [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => $dealer_payable_account_code,
                'name' =>  $stakeholder->full_name . ' Dealer A/P',
                'level' => 5,
                'account_type' => 'credit',
            ];

            (new AccountHead())->create($accountCodeData);
        }
        # Dealer AP Entry
        $this->makeFinancialTransaction($dealer_incentive->site_id, $origin_number, $dealer_payable_account_code, 26, null, 'credit', $dealer_incentive->total_dealer_incentive, NatureOfAccountsEnum::Dealer_Incentive, $dealer_incentive->id);
    }

    // Payment Voucher Transactions
    public function makePaymentVoucherTransaction($payment_voucher, $stakeholder_id)
    {
        $origin_number = AccountLedger::get();
        if (isset($origin_number)) {
            $origin_number = collect($origin_number)->last();
            $origin_number = $origin_number->origin_number + 1;
            $origin_number =  sprintf('%03d', $origin_number);
        } else {
            $origin_number = '001';
        }

        // $stakeholder_type = StakeholderType::where(['stakeholder_id' => $stakeholder_id , 'type' => $payment_voucher->stakeholder_type ] )->first();

        $payable_account = str_replace('-', '', $payment_voucher->account_number);
        // Payment Voucher First Entry Of Customer Or Dealer Or Vendor
        $this->makeFinancialTransaction($payment_voucher->site_id, $origin_number, $payable_account, 4, null, 'debit', $payment_voucher->amount_to_be_paid, NatureOfAccountsEnum::PAYMENT_VOUCHER, $payment_voucher->id);
        // Payment Voucher Second Entry Of Cash Or Bank

        if ($payment_voucher->payment_mode == "Cash") {
            //Cash account credit
            // Cash Transaction
            $cashAccount = (new AccountingStartingCode())->where('site_id', $payment_voucher->site_id)
                ->where('model', 'App\Models\Cash')->where('level', 5)->first();

            if (is_null($cashAccount)) {
                throw new GeneralException('Cash Account is not defined. Please define cash account first.');
            }

            $cashAccount = $cashAccount->level_code . $cashAccount->starting_code;
            $this->makeFinancialTransaction($payment_voucher->site_id, $origin_number, $cashAccount, 4, null, 'credit', $payment_voucher->amount_to_be_paid, NatureOfAccountsEnum::PAYMENT_VOUCHER, $payment_voucher->id);
        }

        if ($payment_voucher->payment_mode == "Online") {
            //Bank account credit
            // Bank Transaction
            $bank = Bank::find($payment_voucher->bank_id);
            $bankAccount = $bank->account_head_code;
            $this->makeFinancialTransaction($payment_voucher->site_id, $origin_number, $bankAccount, 4, null, 'credit', $payment_voucher->amount_to_be_paid, NatureOfAccountsEnum::PAYMENT_VOUCHER, $payment_voucher->id);
        }

        if ($payment_voucher->payment_mode == "Cheque") {
            // Cheuqe Clearance Transaction
            $clearanceAccout = AccountHead::where('name', 'Cheques Clearing Account')->first()->code;
            $this->makeFinancialTransaction($payment_voucher->site_id, $origin_number, $clearanceAccout, 4, null, 'credit', $payment_voucher->amount_to_be_paid, NatureOfAccountsEnum::PAYMENT_VOUCHER, $payment_voucher->id);
        }
    }

    // Payment Voucher Cheque Active Transactions
    public function makePaymentVoucherChequeActiveTransaction($payment_voucher)
    {
        $origin_number = AccountLedger::get();
        if (isset($origin_number)) {
            $origin_number = collect($origin_number)->last();
            $origin_number = $origin_number->origin_number + 1;
            $origin_number =  sprintf('%03d', $origin_number);
        } else {
            $origin_number = '001';
        }


        $clearanceAccout = AccountHead::where('name', 'Cheques Clearing Account')->first()->code;
        $this->makeFinancialTransaction($payment_voucher->site_id, $origin_number, $clearanceAccout, 4, null, 'debit', $payment_voucher->amount_to_be_paid, NatureOfAccountsEnum::PAYMENT_VOUCHER, $payment_voucher->id);

        $bank = Bank::find($payment_voucher->bank_id);
        $bankAccount = $bank->account_head_code;

        $this->makeFinancialTransaction($payment_voucher->site_id, $origin_number, $bankAccount, 4, null, 'credit', $payment_voucher->amount_to_be_paid, NatureOfAccountsEnum::PAYMENT_VOUCHER, $payment_voucher->id);
    }


    // tramsfer File Receipts Transactions
    // for cash
    public function makeTransferReceiptTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new TransferReceipt())->find($receipt_id);

            $amount_in_numbers = $receipt->amount;

            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
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

            $test = $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 30, $receipt->TransferFile->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::TITLE_TRANSFER_RECEIPT, $receipt->id);

            // if disocunt amount availaibe
            if (isset($receipt->discounted_amount) && $receipt->discounted_amount > 0) {
                $cashDiscountAccount = AccountHead::where('name', 'Cash Discount')->where('level', 5)->first()->code;

                // Discount Transaction
                $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashDiscountAccount, 30, $receipt->TransferFile->sales_plan_id, 'debit', $receipt->discounted_amount, NatureOfAccountsEnum::TITLE_TRANSFER_RECEIPT, $receipt->id);
            }

            // Customer AR Transaction
            $customerAccount = collect($receipt->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;

            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->TransferFile->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }
            // $customerAccount = $customerAccount[0];
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 30, $receipt->TransferFile->sales_plan_id, 'credit', $amount_in_numbers, NatureOfAccountsEnum::TITLE_TRANSFER_RECEIPT, $receipt->id);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    // for cheque
    public function makeTransferReceiptChequeTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new TransferReceipt())->find($receipt_id);

            $amount_in_numbers = $receipt->amount;

            $clearanceAccout = AccountHead::where('name', 'Cheques Clearing Account')->first()->code;
            $stakeholder = Stakeholder::where('cnic', $receipt->stakeholder->cnic)->first();
            $stakeholderType = StakeholderType::where('stakeholder_id', $stakeholder->id)->where('type', 'C')->first();
            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }

            // Cheque Transaction
            $cashAccount = $clearanceAccout;
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $cashAccount, 32, $receipt->TransferFile->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::TITLE_TRANSFER_RECEIPT, $receipt->id);

            // Customer AR Transaction
            $customerAccount = collect($receipt->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->TransferFile->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 32, $receipt->TransferFile->sales_plan_id, 'credit', $receipt->amount, NatureOfAccountsEnum::TITLE_TRANSFER_RECEIPT, $receipt->id);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    // in case of cheque active
    public function makeTransferReceiptActiveTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new TransferReceipt())->find($receipt_id);

            $amount_in_numbers = $receipt->amount;

            $bankAccount = $receipt->bank->account_head_code;
            $origin_number = AccountLedger::where('account_action_id', 32)->get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = (int)$origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }
            // bank Transaction
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $bankAccount, 32, $receipt->TransferFile->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::TITLE_TRANSFER_RECEIPT, $receipt->id);

            // Clearing account transaction
            $clearanceAccout = AccountHead::where('name', 'Cheques Clearing Account')->first()->code;

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $clearanceAccout, 32, $receipt->TransferFile->sales_plan_id, 'credit', $receipt->amount, NatureOfAccountsEnum::TITLE_TRANSFER_RECEIPT, $receipt->id);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }

    // in case of online
    public function makeTransferReceiptOnlineTransaction($receipt_id)
    {
        try {
            DB::beginTransaction();

            $receipt = (new TransferReceipt())->find($receipt_id);

            $amount_in_numbers = $receipt->amount;

            $bankAccount = $receipt->bank->account_head_code;
            $origin_number = AccountLedger::get();

            if (isset($origin_number)) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }
            // bank Transaction
            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $bankAccount, 31, $receipt->TransferFile->sales_plan_id, 'debit', $amount_in_numbers, NatureOfAccountsEnum::TITLE_TRANSFER_RECEIPT, $receipt->id);

            // Customer AR Transaction
            $customerAccount = collect($receipt->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
            $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

            if (is_null($customerAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 31, $receipt->TransferFile->sales_plan_id, 'credit', $receipt->amount, NatureOfAccountsEnum::TITLE_TRANSFER_RECEIPT, $receipt->id);

            DB::commit();
            return 'transaction_completed';
        } catch (GeneralException | Exception $ex) {
            DB::rollBack();
            return $ex;
        }
    }


    // in case of other payment mode
    public function makeTransferReceiptOtherTransaction($receipt_id)
    {

        $origin_number = AccountLedger::get();
        if (isset($origin_number)) {
            $origin_number = collect($origin_number)->last();
            $origin_number = $origin_number->origin_number + 1;
            $origin_number =  sprintf('%03d', $origin_number);
        } else {
            $origin_number = '001';
        }

        $receipt = (new TransferReceipt())->find($receipt_id);

        if (isset($receipt->customer_ap_amount) && (float)$receipt->customer_ap_amount > 0) {

            $customerPayableAccount = StakeholderType::where('stakeholder_id', $receipt->stakeholder->id)->where('type', 'C')->first()->payable_account;

            if (is_null($customerPayableAccount)) {
                throw new GeneralException('Customer Account is not defined. Please define customer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerPayableAccount, 35, $receipt->TransferFile->sales_plan_id, 'debit', $receipt->customer_ap_amount, NatureOfAccountsEnum::Customer_AP_Account, $receipt->id);
        }

        if (isset($receipt->dealer_ap_amount) && (float)$receipt->dealer_ap_amount) {

            $dealerPayableAccount = StakeholderType::where('stakeholder_id', $receipt->stakeholder->id)->where('type', 'D')->first()->payable_account;


            if (is_null($dealerPayableAccount)) {
                throw new GeneralException('Dealer Account is not defined. Please define dealer account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $dealerPayableAccount, 35, $receipt->TransferFile->sales_plan_id, 'debit', $receipt->dealer_ap_amount, NatureOfAccountsEnum::Dealer_AP_Account, $receipt->id);
        }

        if (isset($receipt->vendor_ap_amount) && (float)$receipt->vendor_ap_amount) {

            $vendorPayableAccount = StakeholderType::where('stakeholder_id', $receipt->stakeholder->id)->where('type', 'V')->first()->payable_account;

            if (is_null($vendorPayableAccount)) {
                throw new GeneralException('Vendor Account is not defined. Please define vendor account first.');
            }

            $this->makeFinancialTransaction($receipt->site_id, $origin_number, $vendorPayableAccount, 35, $receipt->TransferFile->sales_plan_id, 'debit', $receipt->vendor_ap_amount, NatureOfAccountsEnum::Vendor_AP_Account, $receipt->id);
        }


        // Customer AR Transaction
        $customerAccount = collect($receipt->stakeholder->stakeholder_types)->where('type', 'C')->first()->receivable_account;
        $customerAccount = collect($customerAccount)->where('unit_id', $receipt->unit_id)->first();

        if (is_null($customerAccount)) {
            throw new GeneralException('Customer Account is not defined. Please define customer account first.');
        }

        $this->makeFinancialTransaction($receipt->site_id, $origin_number, $customerAccount['account_code'], 35, $receipt->TransferFile->sales_plan_id, 'credit', $receipt->amount, NatureOfAccountsEnum::TITLE_TRANSFER_RECEIPT, $receipt->id);
    }

    public function makeCustomerApAccount($stakeholder_id)
    {
        // try {
        //     DB::beginTransaction();
        $stakeholder = Stakeholder::find($stakeholder_id);
        $stakeholderType = StakeholderType::where(['stakeholder_id' => $stakeholder_id, 'type' => 'C'])->first();

        if ($stakeholderType->payable_account == null) {
            $stakeholderAllType = StakeholderType::where('type', 'C')->where('payable_account', '!=', null)->get();

            if (count($stakeholderAllType) > 0) {
                $stakeholderTypeLastCode = collect($stakeholderAllType)->last();
                if (isset($stakeholderTypeLastCode->payable_account)) {
                    $customer_payable_account_code = (float)$stakeholderTypeLastCode->payable_account + 1;
                } else {
                    $customer_payable_account_code = '20201010000001';
                }
            } else {
                $customer_payable_account_code = '20201010000001';
            }

            $accountCodeData = [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => (string)$customer_payable_account_code,
                'name' =>  $stakeholder->full_name . ' Customer A/P',
                'level' => 5,
                'account_type' => 'credit',
            ];

            (new AccountHead())->create($accountCodeData);

            // add payable code to stakeholder type
            $stakeholderType = StakeholderType::where(['stakeholder_id' => $stakeholder_id, 'type' => 'C'])->first();
            $stakeholderType->payable_account = (string)$customer_payable_account_code;
            $stakeholderType->status = true;
            $stakeholderType->update();
        }
        // } catch (GeneralException | Exception $ex) {
        //     DB::rollBack();
        //     return $ex;
        // }
    }

    public function makeDealerApAccount($stakeholder_id)
    {
        // try {
        //     DB::beginTransaction();
        $stakeholder = Stakeholder::find($stakeholder_id);
        $stakeholderType = StakeholderType::where(['stakeholder_id' => $stakeholder_id, 'type' => 'D'])->first();

        if ($stakeholderType->payable_account == null) {
            $stakeholderAllType = StakeholderType::where('type', 'D')->where('payable_account', '!=', null)->get();

            if (count($stakeholderAllType) > 0) {
                $stakeholderTypeLastCode = collect($stakeholderAllType)->last();
                if (isset($stakeholderTypeLastCode->payable_account)) {
                    $dealer_payable_account_code = (float)$stakeholderTypeLastCode->payable_account + 1;
                } else {
                    $dealer_payable_account_code = '20201020000001';
                }
            } else {
                $dealer_payable_account_code = '20201020000001';
            }

            $accountCodeData = [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => (string)$dealer_payable_account_code,
                'name' =>  $stakeholder->full_name . ' Dealer A/P',
                'level' => 5,
                'account_type' => 'credit',
            ];

            $code = (new AccountHead())->create($accountCodeData);

            // add payable code to stakeholder type
            $stakeholderType = StakeholderType::where(['stakeholder_id' => $stakeholder_id, 'type' => 'D'])->first();
            $stakeholderType->payable_account = (string)$dealer_payable_account_code;
            $stakeholderType->status = true;
            $stakeholderType->update();
        }
        // } catch (GeneralException | Exception $ex) {
        //     DB::rollBack();
        //     return $ex;
        // }
    }

    public function makeVendorApAccount($stakeholder_id)
    {
        // try {
        //     DB::beginTransaction();
        $stakeholder = Stakeholder::find($stakeholder_id);
        $stakeholderType = StakeholderType::where(['stakeholder_id' => $stakeholder_id, 'type' => 'V'])->first();

        if ($stakeholderType->payable_account == null) {
            $stakeholderAllType = StakeholderType::where('type', 'V')->where('payable_account', '!=', null)->get();

            if (count($stakeholderAllType) > 0) {
                $stakeholderTypeLastCode = collect($stakeholderAllType)->last();
                if (isset($stakeholderTypeLastCode->payable_account)) {
                    $vendor_payable_account_code = (float)$stakeholderTypeLastCode->payable_account + 1;
                } else {
                    $vendor_payable_account_code = '20201030000001';
                }
            } else {
                $vendor_payable_account_code = '20201030000001';
            }

            $accountCodeData = [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => (string)$vendor_payable_account_code,
                'name' =>  $stakeholder->full_name . ' Supplier A/P',
                'level' => 5,
                'account_type' => 'credit',
            ];

            $code = (new AccountHead())->create($accountCodeData);

            // add payable code to stakeholder type
            $stakeholderType = StakeholderType::where(['stakeholder_id' => $stakeholder_id, 'type' => 'V'])->first();
            $stakeholderType->payable_account = (string)$vendor_payable_account_code;
            $stakeholderType->status = true;
            $stakeholderType->update();
        }
        // } catch (GeneralException | Exception $ex) {
        //     DB::rollBack();
        //     return $ex;
        // }
    }

    public function makeInvestorDealReceivableTransaction($id)
    {
        // try {
        //     DB::beginTransaction();

            $investor_deal = StakeholderInvestor::find($id);
            $investor_type = StakeholderType::where(['stakeholder_id' => $investor_deal->investor_id, 'type' => 'I'])->first();
            $receivable_account_code = $this->makeInvsetorReceivableAccounts($investor_deal);

            if(isset( $investor_type->payable_account)){
                $account_payable_code = $investor_type->payable_account;
            }
            else{
                $account_payable_code = $this->makeInvsetorPayableAccounts($investor_deal);
            }

            $arrStakeholderAccount = $investor_type->receivable_account;
            $arrStakeholderAccount[] = [
                "deal_id" => $id,
                "account_code" => $receivable_account_code,
                "default" => true,
                "active" => true
            ];

            $investor_type->receivable_account = $arrStakeholderAccount;
            $investor_type->payable_account = $account_payable_code;
            $investor_type->save();

            $origin_number = AccountLedger::get();
            if (isset($origin_number) && count($origin_number) > 0) {

                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }

            // AR Transaction
            $this->makeFinancialTransaction($investor_deal->site_id, $origin_number, $receivable_account_code, 38, null, 'debit', $investor_deal->total_received_amount, NatureOfAccountsEnum::INVESTOR_DEAL, $investor_deal->id);

            //  AP Transaction
            $this->makeFinancialTransaction($investor_deal->site_id, $origin_number, $account_payable_code, 38, null, 'credit', $investor_deal->total_received_amount, NatureOfAccountsEnum::INVESTOR_DEAL, $investor_deal->id);


            $investor_deal->status = 'approved';
            $investor_deal->approved_by = Auth::user()->id;
            $investor_deal->approved_date = now();
            $investor_deal->update();
        //     DB::commit();
        //     return 'transaction_completed';
        // } catch (GeneralException | Exception $ex) {
        //     DB::rollBack();
        //     return $ex;
        // }
    }

    public function makeInvsetorReceivableAccounts($investor_deal)
    {
        // Get Investor Receivable Starting Code (10-20-11-0001) (4th Level)
        $StartingCode = '10201100010000';
        $EndingCode = '1020120001000';

        $accountCodes = AccountHead::whereBetween('code', [$StartingCode, $EndingCode])->where('level', 5)->get();
        if (isset($accountCodes) && count($accountCodes) > 0) {
            $last_investor_code = collect($accountCodes)->last()->code;
            $account = (float)$last_investor_code + 1;

            $accountCodeData = [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => (string)$account,
                'name' =>  $investor_deal->investor->full_name . ' Investor  A/R  ' . $investor_deal->serial_number,
                'level' => 5,
                'account_type' => 'debit',
            ];
            $code = (new AccountHead())->create($accountCodeData);
            return  (string)$account;
        } else {
            $account = '10201100010001';
            $accountCodeData = [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => (string)$account,
                'name' =>  $investor_deal->investor->full_name . ' Investor  A/R ' . $investor_deal->serial_number,
                'level' => 5,
                'account_type' => 'debit',
            ];
            $code = (new AccountHead())->create($accountCodeData);
            return (string)$account;
        }
    }

    public function makeInvsetorPayableAccounts($investor_deal)
    {
        $stakeholderAllType = StakeholderType::where('type', 'I')->where('payable_account', '!=', null)->orderBy('id','desc')->get();
        dd($stakeholderAllType);
        if (count($stakeholderAllType) > 0) {
            $stakeholderTypeLastCode = collect($stakeholderAllType)->last();

            if (isset($stakeholderTypeLastCode->payable_account)) {
                $investor_payable_account_code = (float)$stakeholderTypeLastCode->payable_account + 1;
            } else {
                $investor_payable_account_code = '20200100010001';
            }
        } else {
            $investor_payable_account_code = '20200100010001';
        }

        $accountCodeData = [
            'site_id' => 1,
            'modelable_id' => 1,
            'modelable_type' => 'App\Models\StakeholderType',
            'code' => (string)$investor_payable_account_code,
            'name' =>  $investor_deal->investor->full_name . ' Investor  A\P ',
            'level' => 5,
            'account_type' => 'credit',
        ];

        $code = (new AccountHead())->create($accountCodeData);

        return (string)$investor_payable_account_code;
    }

    public function makeInvestorDealReceivableReceiptTransaction($id)
    {
        // try {
        //     DB::beginTransaction();

            $deal_receipt = InvsetorDealsReceipt::find($id);

            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = $origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }
            // Investor AR Transaction
            $investorAccount = StakeholderType::where('stakeholder_id',$deal_receipt->investor_id)->where('type','I')->first()->receivable_account;

            $investorAccount = collect($investorAccount)->where('deal_id', $deal_receipt->investor_deal_id)->first();
            // ;
            $this->makeFinancialTransaction($deal_receipt->site_id, $origin_number, $investorAccount['account_code'], 39, null, 'credit', $deal_receipt->total_received_amount, NatureOfAccountsEnum::INVESTOR_DEAL_RECEIPT, $deal_receipt->id);

            $cashAccount = (new AccountingStartingCode())->where('site_id', $deal_receipt->site_id)
                ->where('model', 'App\Models\Cash')->where('level', 5)->first();

            if (is_null($cashAccount)) {
                throw new GeneralException('Cash Account is not defined. Please define cash account first.');
            }

            $cashAccount = $cashAccount->level_code . $cashAccount->starting_code;
            if($deal_receipt->mode_of_payment == 'Cash'){
                $this->makeFinancialTransaction($deal_receipt->site_id, $origin_number, $cashAccount, 39, null, 'debit', $deal_receipt->total_received_amount, NatureOfAccountsEnum::INVESTOR_DEAL_RECEIPT, $deal_receipt->id);

            }

            if ($deal_receipt->payment_mode == "Online") {
                //Bank account credit
                // Bank Transaction
                $bank = Bank::find($deal_receipt->bank_id);
                $bankAccount = $bank->account_head_code;
                dd($bank);
                $this->makeFinancialTransaction($deal_receipt->site_id, $origin_number, $bankAccount, 39, null, 'debit', $deal_receipt->total_received_amount, NatureOfAccountsEnum::INVESTOR_DEAL_RECEIPT, $deal_receipt->id);
            }

            if ($deal_receipt->payment_mode == "Cheque") {
                // Cheuqe Clearance Transaction
                $clearanceAccout = AccountHead::where('name', 'Cheques Clearing Account')->first()->code;
                dd($clearanceAccout);
                $this->makeFinancialTransaction($deal_receipt->site_id, $origin_number, $clearanceAccout, 39, null, 'debit', $deal_receipt->total_received_amount, NatureOfAccountsEnum::INVESTOR_DEAL_RECEIPT, $deal_receipt->id);
            }

        //     DB::commit();
        //     return 'transaction_completed';
        // } catch (GeneralException | Exception $ex) {
        //     DB::rollBack();
        //     return $ex;
        // }
    }

    public function makeInvestorReceiptActive($id)
    {
        $deal_receipt = InvsetorDealsReceipt::find($id);

            $origin_number = AccountLedger::get();
            if (isset($origin_number)) {
                $origin_number = collect($origin_number)->last();
                $origin_number = (int)$origin_number->origin_number + 1;
                $origin_number =  sprintf('%03d', $origin_number);
            } else {
                $origin_number = '001';
            }

            if($deal_receipt->status == 'inactive'){

                $bankAccount = $deal_receipt->bank->account_head_code;
                $this->makeFinancialTransaction($deal_receipt->site_id, $origin_number, $bankAccount, 39, null, 'debit', $deal_receipt->total_received_amount, NatureOfAccountsEnum::INVESTOR_DEAL_RECEIPT, $deal_receipt->id);

                $clearanceAccout = AccountHead::where('name', 'Cheques Clearing Account')->first()->code;
                $this->makeFinancialTransaction($deal_receipt->site_id, $origin_number, $clearanceAccout, 39, null, 'credit', $deal_receipt->total_received_amount, NatureOfAccountsEnum::INVESTOR_DEAL_RECEIPT, $deal_receipt->id);

            }
    }


}
