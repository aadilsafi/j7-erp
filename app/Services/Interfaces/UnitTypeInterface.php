<?php

namespace App\Services\Interfaces;

interface UnitTypeInterface
{
    public function model();

    public function getByAll();
    public function getById($id);
    public function getAllWithTree();

    public function store($inputs);
    public function update($inputs, $id);

    public function destroy($id);
}
