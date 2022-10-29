<?php

namespace App\Services\FinancialTransactions;

interface FinancialTransactionInterface
{
    public function makeSalesPlanTransaction($sales_plan_id);

    public function saveAccountHead($site_id, $model, $accountName, $accountCode, $level);
}
