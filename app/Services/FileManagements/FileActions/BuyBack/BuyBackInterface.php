<?php

namespace App\Services\FileManagements\FileActions\BuyBack;

interface BuyBackInterface
{
    public function model();

    public function getByAll($site_id);
    public function getById($site_id, $id);

    public function store($site_id, $inputs);

    public function update($site_id, $id, $inputs);

    public function destroy($site_id, $inputs);
}
