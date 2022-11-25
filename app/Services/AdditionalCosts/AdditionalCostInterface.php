<?php

namespace App\Services\AdditionalCosts;

interface AdditionalCostInterface
{
    public function model();

    public function getByAll();
    public function getById($site_id, $id);
    public function getAllWithTree($site_id);

    public function store($site_id, $inputs, $customFields);
    public function update($site_id, $inputs, $id, $customFields );

    public function destroy($site_id, $id);
}
