<?php

namespace App\Services\RebateIncentive;

use App\Models\RebateIncentiveModel;
use App\Services\RebateIncentive\RebateIncentiveInterface;

class RebateIncentiveService implements RebateIncentiveInterface
{

    public function model()
    {
        return new RebateIncentiveModel();
    }

    // Get
    public function getByAll($site_id)
    {
        return $this->model()->whereSiteId($site_id)->get();
    }

    public function getById($site_id, $id)
    {

    }

    // Store
    public function store($site_id, $inputs)
    {

    }

    public function update($site_id, $id, $inputs)
    {

    }

    public function destroy($site_id, $inputs)
    {
        $this->model()->whereIn('id', $inputs)->delete();

        return true;
    }
}
