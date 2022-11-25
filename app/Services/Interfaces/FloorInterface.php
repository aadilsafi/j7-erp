<?php

namespace App\Services\Interfaces;

interface FloorInterface
{
    public function model();

    public function getByAll($site_id);
    public function getById($site_id, $id);

    public function store($site_id, $inputs, $customFields );
    public function storeInBulk($site_id, $user_id, $inputs, $isFloorActive = false);

    public function update($site_id, $id, $inputs, $customFields);

    public function destroy($site_id, $id);
}
