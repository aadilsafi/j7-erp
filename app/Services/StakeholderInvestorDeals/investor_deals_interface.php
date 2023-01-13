<?php

namespace App\Services\StakeholderInvestorDeals;

interface investor_deals_interface
{
    public function model();
    public function store($site_id, $inputs);
}
