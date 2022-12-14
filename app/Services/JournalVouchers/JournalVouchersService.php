<?php

namespace App\Services\JournalVouchers;

use App\Models\JournalVoucher;
use App\Services\JournalVouchers\JournalVouchersInterface;

class JournalVouchersService implements JournalVouchersInterface
{
    public function model()
    {
        return new JournalVoucher();
    }

    // Store
    public function store($site_id, $inputs)
    {

    }

    public function update($site_id, $id, $inputs)
    {

    }

    public function destroy($site_id, $inputs)
    {

    }

}
