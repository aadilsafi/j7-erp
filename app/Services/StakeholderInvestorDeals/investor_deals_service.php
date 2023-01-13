<?php

namespace App\Services\StakeholderInvestorDeals;

use App\Models\StakeholderInvestor;
use App\Services\StakeholderInvestorDeals\investor_deals_interface;
use Exception;
use Illuminate\Support\Str;

class investor_deals_service implements investor_deals_interface
{

    public function model()
    {
        return new StakeholderInvestor();
    }

    // Store
    public function store($site_id, $inputs)
    {

    }

}
