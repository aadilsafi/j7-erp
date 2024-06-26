<?php

namespace App\Services\Team\Interface;

interface TeamInterface
{
    public function model();

    public function getByAll($site_id);

    public function getAllWithTree();

    public function store($site_id, $inputs, $customFields);

    public function getById($site_id, $id);

    public function update($site_id, $id, $inputs, $customFields);

    public function destroySelected($id);
}
