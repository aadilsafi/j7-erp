<?php

namespace App\Services\InvestorDealsReceipt;

interface DealReceiptInterface
{
    public function model();
    public function store($site_id, $inputs);

}
