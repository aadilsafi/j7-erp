<?php

namespace App\Services\FinancialTransactions;

interface FinancialTransactionInterface
{
    public function makeSalesPlanTransaction($sales_plan_id);

    public function makeDisapproveSalesPlanTransaction($sales_plan_id);

    public function saveAccountHead($site_id, $model, $accountName, $accountCode, $level,$account_type);

    public function makeFinancialTransaction($site_id, $origin_number, $account_code, $account_action, $sales_plan, $type, $amount, $nature_of_account, $balance = 0);

    public function makeReceiptTransaction($receipt_id); // for cash

    public function makeReceiptChequeTransaction($receipt_id); // for cheque

    public function makeReceiptActiveTransaction($receipt_id); // for cheque Active

    public function makeReceiptOnlineTransaction($receipt_id); // for online

    public function makeReceiptRevertCashTransaction($receipt_id); // for Revert Receipt cash

    public function makeReceiptRevertChequeTransaction($receipt_id); // for Revert Receipt cheque

    public function makeReceiptRevertOnlineTransaction($receipt_id); //for online Revert receipt

    public function makeReceiptOtherTransaction($receipt_id); // for other

    public function makeBuyBackTransaction($site_id, $unit_id, $customer_id, $file_id);

    public function makeFileCancellationTransaction($site_id, $unit_id, $customer_id, $file_id);

    public function makeFileTitleTransferTransaction($site_id, $unit_id, $customer_id, $file_id);

    public function makeFileResaleTransaction($site_id, $unit_id, $customer_id, $file_id);

    public function makeRebateIncentiveTransaction($rebate_id);

    public function makeDealerIncentiveTransaction($dealer_incentive_id);

    public function makePaymentVoucherTransaction($payment_voucher, $stakeholder_id);

    public function makePaymentVoucherChequeActiveTransaction($payment_voucher);

    // transfer Receipts Transactions
    public function makeTransferReceiptTransaction($receipt_id); // for cash

    public function makeTransferReceiptChequeTransaction($receipt_id); // for cheque

    public function makeTransferReceiptActiveTransaction($receipt_id); // for cheque Active

    public function makeTransferReceiptOnlineTransaction($receipt_id); // for online

    public function makeTransferReceiptOtherTransaction($receipt_id); // for other

    public function makeCustomerApAccount($stakeholder_id);

    public function makeDealerApAccount($stakeholder_id);

    public function makeVendorApAccount($stakeholder_id);

}
