<?php

namespace App\Services\CustomFields;

interface CustomFieldInterface
{
    public function model();

    public function getAll($site_id, $relationships = []);
    public function getById($site_id, $id, $relationships = []);

    public function getAllByModel($site_id, $model, $relationships = []);

    public function store($site_id, $inputs);
    public function update($site_id, $id, $inputs);

    public function destroy($site_id, $id);
}
