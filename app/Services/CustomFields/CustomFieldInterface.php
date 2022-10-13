<?php

namespace App\Services\CustomFields;

interface CustomFieldInterface
{
    public function model();

    public function getAll($relationships = []);
    public function getById($id, $relationships = []);

    public function store($inputs);
    public function update($id, $inputs);

    public function destroy($id);
}
