<?php

namespace App\Services\Interfaces;

interface UnitInterface
{
    public function model();

    public function getByAll($site_id, $floor_id);
    public function getById($site_id, $floor_id, $id, $relationships = []);

    public function store($site_id, $floor_id, $inputs, $isUnitActive = true);
    public function storeInBulk($site_id, $floor_id, $inputs, $isUnitActive = false);

    public function update($site_id, $floor_id, $id, $inputs);

    public function destroy($site_id, $floor_id, $id);
}
