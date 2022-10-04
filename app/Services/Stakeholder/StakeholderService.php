<?php

namespace App\Services\Stakeholder;

use App\Models\{
    Stakeholder,
    StakeholderContact,
    StakeholderType,
};
use Illuminate\Support\Facades\Storage;
use App\Utils\Enums\StakeholderTypeEnum;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use Illuminate\Support\Facades\DB;
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
        DB::transaction(function () use ($site_id, $inputs) {

            $data = [
                'site_id' => decryptParams($site_id),
                'full_name' => $inputs['full_name'],
                'father_name' => $inputs['father_name'],
                'occupation' => $inputs['occupation'],
                'designation' => $inputs['designation'],
                'cnic' => $inputs['cnic'],
                'ntn' => $inputs['ntn'],
                'contact' => $inputs['contact'],
                'address' => $inputs['address'],
                'parent_id' => $inputs['parent_id'],
                'comments' => $inputs['comments'],
                'relation' => $inputs['relation'],
            ];
            // dd($inputs);

            $stakeholder = $this->model()->create($data);

            if (isset($inputs['attachment'])) {
                foreach ($inputs['attachment'] as $attachment) {
                    $stakeholder->addMedia($attachment)->toMediaCollection('stakeholder_cnic');
                }
            }

            if (isset($inputs['contact-persons']) && count($inputs['contact-persons']) > 0) {
                $contacts = [];
                foreach ($inputs['contact-persons'] as $contact) {

                    $data = [
                        'stakeholder_id' => $stakeholder->id,
                        'full_name' => $contact['full_name'],
                        'father_name' => $contact['father_name'],
                        'occupation' => $contact['occupation'],
                        'designation' => $contact['designation'],
                        'cnic' => $contact['cnic'],
                        'ntn' => $contact['ntn'],
                        'contact' => $contact['contact'],
                        'address' => $contact['address'],
                    ];

                    $contacts[] = new StakeholderContact($data);
                }
                $stakeholder->contacts()->saveMany($contacts);
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
        });
    }

    public function update($site_id, $id, $inputs)
    {
        DB::transaction(function () use ($site_id, $id, $inputs) {
            $stakeholder = $this->model()->find($id);
            $data = [
                'full_name' => $inputs['full_name'],
                'father_name' => $inputs['father_name'],
                'occupation' => $inputs['occupation'],
                'designation' => $inputs['designation'],
                'cnic' => $inputs['cnic'],
                'ntn' => $inputs['ntn'],
                'contact' => $inputs['contact'],
                'address' => $inputs['address'],
                'parent_id' => $inputs['parent_id'],
                'comments' => $inputs['comments'],
                'relation' => $inputs['relation'],
            ];
            $stakeholder->update($data);

            $stakeholder->clearMediaCollection('stakeholder_cnic');

            if (isset($inputs['attachment'])) {
                foreach ($inputs['attachment'] as $attachment) {
                    $stakeholder->addMedia($attachment)->toMediaCollection('stakeholder_cnic');
                }
            }

            if (isset($inputs['stakeholder_type'])) {
                foreach ($inputs['stakeholder_type'] as $key => $value) {
                    (new StakeholderType())->where([
                        'stakeholder_id' => $stakeholder->id,
                        'type' => $key,
                    ])->update([
                        'status' => true,
                    ]);
                }
            }
            // dd($inputs);
            $stakeholder->contacts()->delete();
            if (isset($inputs['contact-persons']) && count($inputs['contact-persons']) > 0) {
                $contacts = [];
                foreach ($inputs['contact-persons'] as $contact) {
                    $contacts[] = new StakeholderContact($contact);
                }
                $stakeholder->contacts()->saveMany($contacts);
            }
            return $stakeholder;
        });
    }

    public function destroy($site_id, $id)
    {
        DB::transaction(function () use ($site_id, $id) {
            $id = decryptParams($id);
            $stakeholder = getLinkedTreeData($this->model(), $id);
            $stakeholderIDs = array_merge($id, array_column($stakeholder, 'id'));

            $this->model()->whereIn('id', $stakeholderIDs)->get()->each(function ($row) {
                $row->delete();
            });

            return true;
        });
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
            'ntn' => '',
            'contact' => '',
            'address' => '',
            'comments' => '',
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
