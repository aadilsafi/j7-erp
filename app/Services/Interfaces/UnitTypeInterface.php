<?php

namespace App\Services\Interfaces;

interface UnitTypeInterface
{
    public function model(mixed $parameters = []);

    public function getByAll();
    public function getById($id);
    public function getAllWithTree($site_id);

    public function store($site_id, $inputs, $customFields);
    public function update($site_id, $inputs, $id, $customFields);

    public function destroy($site_id, $id);
}
