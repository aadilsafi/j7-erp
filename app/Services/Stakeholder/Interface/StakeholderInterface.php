<?php

namespace App\Services\Stakeholder\Interface;

interface StakeholderInterface
{
    public function model();

    public function getByAll($site_id);
    public function getByAllWith($site_id, array $relationships = []);

    public function getById($site_id, $id, array $relationships = []);
    public function getAllWithTree();

    public function store($site_id, $inputs);
    public function update($site_id, $id, $inputs);

    public function destroy($site_id, $id);

    public function getEmptyInstance();
}
