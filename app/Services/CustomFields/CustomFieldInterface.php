<?php

namespace App\Services\CustomFields;

interface CustomFieldInterface
{
    public function model();

    public function getByAll();
    public function getById($id);

    public function store($inputs);
    public function update($inputs, $id);

    public function destroy($id);

    public function destroySelected($ids);

}
