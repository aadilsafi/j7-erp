<?php

namespace App\Services\RebateIncentive;

use App\Models\DealerIncentiveModel;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Models\Unit;
use App\Services\DealerIncentive\DealerInterface;
use Illuminate\Support\Facades\DB;

class DealerService implements DealerInterface
{

    public function model()
    {
        return new DealerIncentiveModel();
    }

    // Get
    public function getByAll($site_id)
    {
        return $this->model()->whereSiteId($site_id)->get();
    }

    public function getById($site_id, $id)
    {

        return $this->model()->find($id);
    }

    // Store
    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {

            $rebatedata = [
                'site_id' => $site_id,
                'unit_id' => $inputs['unit_id'],
                'stakeholder_id' => $inputs['stakeholder_id'],
                'stakeholder_data' => json_encode(Stakeholder::find($inputs['stakeholder_id'])),
                'unit_data' => json_encode(Unit::find($inputs['unit_id'])),
                'deal_type' => $inputs['deal_type'],
                'commision_percentage' => $inputs['rebate_percentage'],
                'commision_total' => $inputs['rebate_total'],
                'status' => 0,
                'comments' => $inputs['comments'],
            ];

            $dealer_incentive = $this->model()->create($rebatedata);

            return $dealer_incentive;
        });
    }

    public function update($site_id, $id, $inputs)
    {
    }

    public function destroy($site_id, $inputs)
    {
    }
}
