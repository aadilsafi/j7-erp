<?php

namespace App\Services\Stakeholder\Interface;

interface StakeholderInterface
{
    public function model();

    public function getByAll();
    public function getById($site_id, $id);

    public function store($site_id, $inputs);
    public function update($site_id, $inputs, $id);

    public function destroy($site_id, $id);
}
