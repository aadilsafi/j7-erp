<?php

namespace App\Services\Stakeholder;

use App\Models\Stakeholder;
use App\Models\StakeholderType;
use Illuminate\Support\Facades\Storage;
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

        $firstImageName = $firstImage->getClientOriginalName();
        $secondImageName = $secondImage->getClientOriginalName();
        $attachment = $firstImageName . ',' . $secondImageName;

        if ($inputs['parent_id'] == null) {
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

        $folder = 'app-assets/server-uploads/stakeholders/' . $stakeholder->id . '/';

        if (!file_exists($folder . $firstImageName)) {
            $firstImage->move(public_path('app-assets/server-uploads/stakeholders/' . $stakeholder->id . '/'), $firstImageName);
        }
        if (!file_exists($folder . $secondImageName)) {
            $secondImage->move(public_path('app-assets/server-uploads/stakeholders/' . $stakeholder->id . '/'), $secondImageName);
        }

        $stakeholderTypeData  = [
            [
                'stakeholder_id' => $stakeholder->id,
                'type' => StakeholderTypeEnum::CUSTOMER->value,
                'stakeholder_code' => StakeholderTypeEnum::CUSTOMER->value . '-' . str_pad($stakeholder->id, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => $stakeholder->id,
                'type' => StakeholderTypeEnum::VENDOR->value,
                'stakeholder_code' => StakeholderTypeEnum::VENDOR->value . '-' . str_pad($stakeholder->id, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => $stakeholder->id,
                'type' => StakeholderTypeEnum::DEALER->value,
                'stakeholder_code' => StakeholderTypeEnum::DEALER->value . '-' . str_pad($stakeholder->id, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => $stakeholder->id,
                'type' => StakeholderTypeEnum::NEXT_OF_KIN->value,
                'stakeholder_code' => StakeholderTypeEnum::NEXT_OF_KIN->value . '-' . str_pad($stakeholder->id, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
            [
                'stakeholder_id' => $stakeholder->id,
                'type' => StakeholderTypeEnum::LEAD->value,
                'stakeholder_code' => StakeholderTypeEnum::LEAD->value . '-' . str_pad($stakeholder->id, 3, "0", STR_PAD_LEFT),
                'status' => 0,
            ],
        ];
        $stakeholder_type = StakeholderType::insert($stakeholderTypeData);
        return $stakeholder;
    }

    public function update($site_id, $id, $inputs)
    {
        $firstImage = $inputs['attachment'][0];
        $secondImage = $inputs['attachment'][1];

        $firstImageName = $firstImage->getClientOriginalName();
        $secondImageName = $secondImage->getClientOriginalName();

        $attachment = $firstImageName . ',' . $secondImageName;

        if ($inputs['parent_id'] == null) {
            $inputs['parent_id'] = 0;
        }
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
            'attachment' => $attachment,
        ];
        $stakeholder_data = $this->model()->where('id', $id)->update($data);

        $folder = 'app-assets/server-uploads/stakeholders/' . $id . '/';

        if (!file_exists($folder . $firstImageName)) {
            $firstImage->move(public_path('app-assets/server-uploads/stakeholders/' . $id . '/'), $firstImageName);
        }
        if (!file_exists($folder . $secondImageName)) {
            $secondImage->move(public_path('app-assets/server-uploads/stakeholders/' . $id . '/'), $secondImageName);
        }

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
