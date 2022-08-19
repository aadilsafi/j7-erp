<?php

namespace App\Services\Interfaces;

interface UnitTypeInterface
{
    public function model();

    public function getByAll();
    public function getById($id);
    public function getAllWithTree();

    public function store($site_id, $inputs);
    public function update($site_id, $inputs, $id);

    public function destroy($site_id, $id);
}
