<?php

namespace App\Services\Stakeholder;

use App\Models\Stakeholder;
use App\Models\StakeholderType;
use Illuminate\Support\Facades\Storage;
use App\Utils\Enums\StakeholderTypeEnum;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use Illuminate\Support\Str;

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

    public function getByAllWith($site_id, array $relationships = [])
    {
        return $this->model()->with($relationships)->where('site_id', $site_id)->get();
    }

    public function getAllWithTree()
    {
        $stakeholders = $this->model()->with('stakeholder_types')->get();
        return getStakeholderTreeData(collect($stakeholders), $this->model());
    }

    public function getById($site_id, $id, array $relationships = [])
    {
        if ($id == 0) {
            return $this->getEmptyInstance();
        }

        return $this->model()->with($relationships)->find($id);
    }

    public function store($site_id, $inputs)
    {
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
        ];

        $stakeholder = $this->model()->create($data);


        if (isset($inputs['attachment'])) {
            foreach ($inputs['attachment'] as $attachment) {
                $stakeholder->addMedia($attachment)->toMediaCollection('stakeholder_cnic');
            }
        }

        $stakeholderId = Str::of($stakeholder->id)->padLeft(3, '0');
        $stakeholderTypeData = [];
        foreach (StakeholderTypeEnum::array() as $key => $value) {
            $stakeholderType = [
                'stakeholder_id' => $stakeholder->id,
                'type' => $value,
                'stakeholder_code' => $value . '-' . $stakeholderId,
                'status' => $inputs['stakeholder_type'] == $value ? 1 : 0,
            ];

            if ($inputs['stakeholder_type'] == 'C' || $inputs['parent_id'] > 0) {
                if (in_array($value, ['C', 'L', 'K'])) {
                    $stakeholderType['status'] = 1;
                }
            }

            $stakeholderTypeData[] = $stakeholderType;
        }

        $stakeholder_type = StakeholderType::insert($stakeholderTypeData);
        return $stakeholder;
    }

    public function update($site_id, $id, $inputs)
    {
        if (isset($inputs['attachment'])) {
            $firstImage = $inputs['attachment'][0];
            $secondImage = $inputs['attachment'][1];
            $firstImageName = $firstImage->getClientOriginalName();
            $secondImageName = $secondImage->getClientOriginalName();
            $attachment = $firstImageName . ',' . $secondImageName;
        }
        else{
            $attachment = null;
        }

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
        ];
        $stakeholder_data = $this->model()->where('id', $id)->update($data);

        $folder = 'app-assets/server-uploads/stakeholders/' . $id . '/';
        if (isset($inputs['attachment'])) {
            if (!file_exists($folder . $firstImageName)) {
                $firstImage->move(public_path('app-assets/server-uploads/stakeholders/' . $id . '/'), $firstImageName);
            }
            if (!file_exists($folder . $secondImageName)) {
                $secondImage->move(public_path('app-assets/server-uploads/stakeholders/' . $id . '/'), $secondImageName);
            }
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

    public function getEmptyInstance()
    {
        $stakeholder = [
            'id' => 0,
            'full_name' => '',
            'father_name' => '',
            'occupation' => '',
            'designation' => '',
            'cnic' => '',
            'contact' => '',
            'address' => '',
            'stakeholder_types' => [
                [
                    'stakeholder_id' => 0,
                    'id' => 1,
                    'type' => 'C',
                    'stakeholder_code' => 'C-000',
                    'status' => 0,
                    'created_at' => null,
                    'updated_at' => null,
                ],
                [
                    'stakeholder_id' => 0,
                    'id' => 2,
                    'type' => 'V',
                    'stakeholder_code' => 'V-000',
                    'status' => 0,
                    'created_at' => null,
                    'updated_at' => null,
                ],
                [
                    'stakeholder_id' => 0,
                    'id' => 2,
                    'type' => 'D',
                    'stakeholder_code' => 'D-000',
                    'status' => 0,
                    'created_at' => null,
                    'updated_at' => null,
                ],
                [
                    'stakeholder_id' => 0,
                    'id' => 2,
                    'type' => 'K',
                    'stakeholder_code' => 'K-000',
                    'status' => 0,
                    'created_at' => null,
                    'updated_at' => null,
                ],
                [
                    'stakeholder_id' => 0,
                    'id' => 2,
                    'type' => 'L',
                    'stakeholder_code' => 'L-000',
                    'status' => 0,
                    'created_at' => null,
                    'updated_at' => null,
                ],
            ]
        ];

        return $stakeholder;
    }
}
