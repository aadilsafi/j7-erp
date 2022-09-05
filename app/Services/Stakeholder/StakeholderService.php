<?php

namespace App\Services\Stakeholder;

use App\Models\Stakeholder;
use App\Services\Stakeholder\Interface\StakeholderInterface;

class StakeholderService implements StakeholderInterface
{

    public function model()
    {
        return new Stakeholder();
    }

    // Get
    public function getByAll($site_id)
    {
        return $this->model()->where('site_id', $site_id)->get();
    }

    public function getById($site_id, $id)
    {
        return $this->model()->find($id);
    }

    // Store
    public function store($site_id, $inputs)
    {

    }

    public function update($site_id, $id, $inputs)
    {

    }

    public function destroy($site_id, $id)
    {
    }
}
