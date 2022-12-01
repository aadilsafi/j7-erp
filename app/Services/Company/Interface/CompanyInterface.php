<?php

namespace App\Services\Company\Interface;

interface CompanyInterface
{
    public function model();

    public function getByAll($site_id);

    public function store($site_id, $inputs);

    public function getById($site_id, $id);

    public function update($site_id, $id, $inputs);

    public function destroySelected($id);
}
