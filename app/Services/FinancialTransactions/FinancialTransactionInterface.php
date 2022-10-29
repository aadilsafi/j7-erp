<?php

namespace App\Services\FinancialTransactions;

interface FinancialTransactionInterface
{
    public function makeSalesPlanTransaction($sales_plan_id);
}
