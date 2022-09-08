<?php

namespace App\Services\Stakeholder;

use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Utils\Enums\StakeholderTypeEnum;
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
        $firstImage = $inputs['attachment'][0];
        $secondImage = $inputs['attachment'][1];

        $firstImageName = time().'.'.$firstImage->extension();
        $secondImageName = time().'.'.$secondImage->extension();
        $folder = 'app-assets/stakeholder/cnic/attachments';

        $firstImage->move(public_path('app-assets/stakeholder/cnic/attachments'), $firstImageName);

        $secondImage->move(public_path('app-assets/stakeholder/cnic/attachments'), $secondImageName);

        $attachment = $firstImageName.','.$secondImageName;

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
            'attachment' => $attachment,
        ];

        $stakeholder = $this->model()->create($data);

        $stakeholderTypeData  = [
            [
                'stakeholder_id' => $stakeholder->id,
                'type' => StakeholderTypeEnum::CUSTOMER->value,
                'stakeholder_code' => StakeholderTypeEnum::CUSTOMER->value.'-'.str_pad($stakeholder->id,4,"0",STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => $stakeholder->id,
                'type' => StakeholderTypeEnum::VENDOR->value,
                'stakeholder_code' => StakeholderTypeEnum::VENDOR->value.'-'.str_pad($stakeholder->id,4,"0",STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => $stakeholder->id,
                'type' => StakeholderTypeEnum::DEALER->value,
                'stakeholder_code' => StakeholderTypeEnum::DEALER->value.'-'.str_pad($stakeholder->id,4,"0",STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => $stakeholder->id,
                'type' => StakeholderTypeEnum::NEXT_OF_KIN->value,
                'stakeholder_code' => StakeholderTypeEnum::NEXT_OF_KIN->value.'-'.str_pad($stakeholder->id,4,"0",STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => $stakeholder->id,
                'type' => StakeholderTypeEnum::LEAD->value,
                'stakeholder_code' => StakeholderTypeEnum::LEAD->value.'-'.str_pad($stakeholder->id,4,"0",STR_PAD_LEFT),
                'status' => 0,
            ],
        ];
        $stakeholder_type = StakeholderType::insert($stakeholderTypeData);
        return $stakeholder;

    }

    public function update($site_id, $id, $inputs)
    {
        $data = [
            'full_name' => $inputs['full_name'],
            'father_name' => $inputs['father_name'],
            'occupation' => $inputs['occupation'],
            'designation' => $inputs['designation'],
            'cnic' => $inputs['cnic'],
            'contact' => $inputs['contact'],
            'address' => $inputs['address'],
            'parent_id' => $inputs['parent_id'],
            'relation' => $inputs['relation'],
        ];
        $stakeholder_data = $this->model()->where('id', $id)->update($data);
        // dd($stakeholder_data);
        return $stakeholder_data;
    }

    public function destroy($site_id, $id)
    {
        $id = decryptParams($id);

        $stakeholder = getLinkedTreeData($this->model(), $id);

        $stakeholderIDs = array_merge($id, array_column($stakeholder, 'id'));

        $this->model()->whereIn('id', $stakeholderIDs)->delete();

        return true;
    }
}
