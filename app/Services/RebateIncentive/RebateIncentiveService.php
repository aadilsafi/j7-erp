<?php

namespace App\Services\RebateIncentive;

use App\Models\RebateIncentiveModel;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Models\Unit;
use App\Services\RebateIncentive\RebateIncentiveInterface;
use Illuminate\Support\Facades\DB;

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
        
        return $this->model()->find($id);
    }

    // Store
    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {

            $dealer_id = $inputs['dealer_id'];
            if ($dealer_id == 0) {
                $dealer_data = $inputs['dealer'];

                $dealer = Stakeholder::create([
                    'site_id' => $site_id,
                    'full_name' => $dealer_data['full_name'],
                    'father_name' => $dealer_data['father_name'],
                    'occupation' => $dealer_data['occupation'],
                    'designation' => $dealer_data['designation'],
                    'cnic' => $dealer_data['cnic'],
                    'ntn' => $dealer_data['ntn'],
                    'contact' => $dealer_data['contact'],
                    'address' => $dealer_data['address'],
                    'comments' => $dealer_data['comments'],
                ]);

                $stakeholdertype = [
                    [
                        'stakeholder_id' => $dealer->id,
                        'type' => 'C',
                        'stakeholder_code' => 'C-00' . $dealer->id,
                        'status' => 0,
                    ],
                    [
                        'stakeholder_id' => $dealer->id,
                        'type' => 'V',
                        'stakeholder_code' => 'V-00' . $dealer->id,
                        'status' => 0,
                    ],
                    [
                        'stakeholder_id' => $dealer->id,
                        'type' => 'D',
                        'stakeholder_code' => 'D-00' . $dealer->id,
                        'status' => 1,
                    ],
                    [
                        'stakeholder_id' => $dealer->id,
                        'type' => 'K',
                        'stakeholder_code' => 'K-00' . $dealer->id,
                        'status' => 0,
                    ],
                    [
                        'stakeholder_id' => $dealer->id,
                        'type' => 'L',
                        'stakeholder_code' => 'L-00' . $dealer->id,
                        'status' => 0,
                    ]
                ];

                $stakeholder_type = StakeholderType::insert($stakeholdertype);
                
                $dealer_id = $dealer->id;
            }

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
                'dealer_id' => $dealer_id,
            ];

            $rebate = $this->model()->create($rebatedata);

            return $rebate;
        });
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
