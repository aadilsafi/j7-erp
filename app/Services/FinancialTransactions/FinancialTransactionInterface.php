<?php

namespace App\Services\FinancialTransactions;

interface FinancialTransactionInterface
{
    public function makeSalesPlanTransaction($sales_plan_id);

    public function saveAccountHead($site_id, $model, $accountName, $accountCode, $level);

    public function makeFinancialTransaction($site_id, $account_code, $account_action, $sales_plan, $type, $amount, $nature_of_account, $balance = 0);
}
