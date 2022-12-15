<?php

namespace App\Services\JournalVouchers;

interface JournalVouchersInterface
{
    public function model();

    public function store($site_id, $inputs);

    public function update($site_id, $id, $inputs);

    public function destroy($site_id, $inputs);

}
