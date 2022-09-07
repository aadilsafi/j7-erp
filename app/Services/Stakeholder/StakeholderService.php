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
        // dd($site_id,$inputs);
        // $data = [
        //     'site_id' => decryptParams($site_id),
        //     'full_name' => Ahmed Ali,
        //     'father_name' => Ali RAza,
        //     'occupation' => developer,
        //     'designation' => senior,
        //     'cnic' => 123123123213,
        //     'contact' => 13123213213,
        //     'address' => Rawalpindi,
        //     'parent_id' => 0,
        //     'relation' => '',
        //     'attachment' => '',
        // ];
    }

    public function update($site_id, $id, $inputs)
    {

    }

    public function destroy($site_id, $id)
    {
    }
}
