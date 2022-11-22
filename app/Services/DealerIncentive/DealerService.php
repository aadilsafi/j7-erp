<?php

namespace App\Services\DealerIncentive;

use App\Models\DealerIncentiveModel;
use App\Models\RebateIncentiveModel;
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

            $uids = $inputs['unit_ids'];
            // $uids = array_column($ids,'uid');

            $dealerIncentive = [
                'site_id' => decryptParams($site_id),
                'dealer_id' => $inputs['dealer_id'],
                'dealer_data' => json_encode(Stakeholder::find($inputs['dealer_id'])),
                'dealer_incentive' => $inputs['dealer_incentive'],
                'total_unit_area' => $inputs['total_unit_area'],
                'total_dealer_incentive' => $inputs['total_dealer_incentive'],
                'unit_IDs' => json_encode($uids),
                'status' => 0,
                'comments' => $inputs['comments'],
            ];

            $dealer_incentive = $this->model()->create($dealerIncentive);


            foreach ($uids as $ids) {
                $rebates = RebateIncentiveModel::where('dealer_id', $inputs['dealer_id'])->where('unit_id', $ids)->get();
                foreach ($rebates as $rebate) {
                    $rebate->is_for_dealer_incentive = false;
                    $rebate->save();
                }
            }

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
