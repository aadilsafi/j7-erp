<?php

namespace App\Services\Interfaces;

interface UserBatchInterface
{
    public function model();

    public function getByAll();
    public function getById($site_id, $id);

    public function store($site_id, $user_id, $job_batch_id, $actions, $status);
    public function update($site_id, $inputs, $id);

    public function destroy($site_id, $id);
}
