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

    public function getAllWithTree()
    {
        $stakeholders = $this->model()->all();
        return getStakeholderTreeData(collect($stakeholders), $this->model());
    }

    public function getById($site_id, $id)
    {
        return $this->model()->find($id);
    }

    // Store
    public function store($site_id, $inputs)
    {
        if($inputs['parent_id'] == null){
            $inputs['parent_id'] = 0;
        }
        $data = [
            'site_id' => decryptParams($site_id),
            'full_name' => $inputs['full_name'],
            'father_name' => $inputs['father_name'],
            'occupation' => $inputs['occupation'],
            'designation' => $inputs['designation'],
            'cnic' => $inputs['cnic'],
            'contact' => $inputs['contact'],
            'address' => $inputs['address'],
            'parent_id' => $inputs['parent_id'],
            'relation' => $inputs['relation'],
            'attachment' => json_encode($inputs['attachment']),
        ];

        $stakeholder = $this->model()->create($data);

        return $stakeholder;

    }

    public function update($site_id, $id, $inputs)
    {

    }

    public function destroy($site_id, $id)
    {
    }
}
