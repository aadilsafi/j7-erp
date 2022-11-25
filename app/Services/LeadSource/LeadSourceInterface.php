<?php

namespace App\Services\LeadSource;

interface LeadSourceInterface
{
    public function model();

    public function getByAll($site_id);
    public function getById($site_id, $id);

    public function store($site_id, $inputs, $customFields);

    public function update($site_id, $id, $inputs, $customFields);

    public function destroy($site_id, $inputs);
}
