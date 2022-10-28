<?php

namespace App\Services\FileManagements;

use App\Models\Unit;
use App\Models\SalesPlan;
use App\Models\Stakeholder;
use App\Models\FileManagement;
use App\Services\FileManagements\FileManagementInterface;

class FileManagementService implements FileManagementInterface
{

    public function model()
    {
        return new FileManagement();
    }

    // Get
    public function getByAll($site_id)
    {
        return $this->model()->whereSiteId($site_id)->get();
    }

    public function getById($site_id, $id)
    {
        return $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ])->first();
    }

    // Store
    public function store($site_id, $inputs)
    {

        $sales_plan = (new SalesPlan())->with([
            'additionalCosts', 'installments', 'leadSource', 'receipts'
        ])->where([
            'status' => 1,
            'unit_id' => $inputs['application_form']['unit_id'],
        ])->first();

        $data = [
            'site_id' => decryptParams($site_id),
            'unit_id' => $inputs['application_form']['unit_id'],
            'stakeholder_id' => $inputs['application_form']['stakeholder_id'],
            'unit_data' => json_encode(Unit::find($inputs['application_form']['unit_id'])),
            'stakeholder_data' => json_encode(Stakeholder::find($inputs['application_form']['stakeholder_id'])),
            'registration_no' => $inputs['application_form']['registration_no'],
            'application_no' => $inputs['application_form']['application_no'],
            'deal_type' => $inputs['application_form']['deal_type'],
            'status' => 1,
            'file_action_id' => 1,
            'sales_plan_id'=> $sales_plan->id,
        ];
        $file = $this->model()->create($data);

        if (isset($inputs['application_form']['photo'])) {
            $file->addMedia($inputs['application_form']['photo'])->toMediaCollection('application_form_photo');
            changeImageDirectoryPermission();
        }

        return $file;
    }

    public function update($site_id, $id, $inputs)
    {
        $data = [
            'name' => filter_strip_tags($inputs['lead_source_name']),
        ];

        $leadSource = $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ])->update($data);

        return $leadSource;
    }

    public function destroy($site_id, $inputs)
    {
        $this->model()->whereIn('id', $inputs)->delete();

        return true;
    }
}
