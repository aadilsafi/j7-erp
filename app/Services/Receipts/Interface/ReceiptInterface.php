<?php

namespace App\Services\Receipts\Interface;

interface ReceiptInterface
{
    public function model();

    public function store($site_id, $inputs);
    public function update($site_id, $id, $inputs);

    public function destroy($site_id, $id);
    public function makeActive($site_id, $id);

    public function updateInstallments($inputs);

    public function ImportReceipts($site_id);
    public function revertPayment($site_id, $id);

}
