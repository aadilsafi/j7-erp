<?php

namespace App\Services\TransferFileReceipts;

interface TransferFileReceiptInterface
{
    public function model();

    public function store($site_id, $inputs);

    public function destroy($site_id, $id);
    public function makeActive($site_id, $id);
}
