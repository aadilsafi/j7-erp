<?php

namespace App\Services\Stakeholder;

use App\DataTables\BlacklistedStakeholderDataTable;
use App\Models\{
    BacklistedStakeholder,
    CustomFieldValue,
    Stakeholder,
    StakeholderContact,
    StakeholderNextOfKin,
    StakeholderType,
};
use Illuminate\Support\Facades\Storage;
use App\Utils\Enums\StakeholderTypeEnum;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

    public function store($site_id, $inputs, $customFields)
    {
        DB::transaction(function () use ($site_id, $inputs, $customFields) {
            if ($inputs['stakeholder_as'] == 'i') {
                $data = [
                    'full_name' => $inputs['full_name'],
                    'father_name' => $inputs['father_name'],
                    'occupation' => $inputs['occupation'],
                    'designation' => $inputs['designation'],
                    'cnic' => $inputs['cnic'],
                    'passport_no' => $inputs['passport_no'],
                    'ntn' => $inputs['ntn'],
                    'email' => $inputs['individual_email'],
                    'office_email' => $inputs['office_email'],
                    'mobile_contact' => $inputs['mobile_contact'],
                    'mobileContactCountryDetails' => $inputs['mobileContactCountryDetails'],
                    'office_contact' => $inputs['office_contact'],
                    'OfficeContactCountryDetails' => $inputs['OfficeContactCountryDetails'],
                    'referred_by' => $inputs['referred_by'],
                    'source' => $inputs['source'],
                    'date_of_birth' => $inputs['dob'],
                    'is_local' => $inputs['is_local'],
                    'nationality' => $inputs['nationality'],
                ];
            } else if ($inputs['stakeholder_as'] == 'c') {
                $data = [
                    'full_name' => $inputs['company_name'],
                    'industry' => $inputs['industry'],
                    'office_contact' => $inputs['company_office_contact'],
                    'OfficeContactCountryDetails' => $inputs['CompanyOfficeContactCountryDetails'],
                    'mobile_contact' => $inputs['company_optional_contact'],
                    'mobileContactCountryDetails' => $inputs['companyMobileContactCountryDetails'],
                    'email' => $inputs['company_email'],
                    'office_email' => $inputs['company_office_email'],
                    'website' => $inputs['website'],
                    'parent_company' => $inputs['parent_company'],
                    'cnic' => $inputs['registration'],
                    'strn' => $inputs['strn'],
                    'ntn' => $inputs['company_ntn'],
                    'origin' => $inputs['origin'],
                ];
            }
            $data['stakeholder_as'] = $inputs['stakeholder_as'];
            $data['site_id'] = decryptParams($site_id);
            // residential address fields
            $data['residential_address_type'] = $inputs['residential_address_type'];
            $data['residential_address'] = $inputs['residential_address'];
            $data['residential_postal_code'] = $inputs['residential_postal_code'];
            $data['residential_country_id'] = isset($inputs['residential_country']) && $inputs['residential_country'] > 0 ? $inputs['residential_country'] : 167;
            $data['residential_state_id'] = isset($inputs['residential_state']) ? $inputs['residential_state'] : 0;
            $data['residential_city_id'] = isset($inputs['residential_city']) ? $inputs['residential_city'] : 0;

            //mailing address fields
            $data['mailing_address_type'] = $inputs['mailing_address_type'];
            $data['mailing_address'] = $inputs['mailing_address'];
            $data['mailing_postal_code'] = $inputs['mailing_postal_code'];
            $data['mailing_country_id'] = isset($inputs['mailing_country']) && $inputs['mailing_country'] > 0 ? $inputs['mailing_country'] : 167;
            $data['mailing_state_id'] = isset($inputs['mailing_state']) ? $inputs['mailing_state'] : 0;
            $data['mailing_city_id'] = isset($inputs['mailing_city']) ? $inputs['mailing_city'] : 0;

            $data['comments'] = $inputs['comments'];

            $data['nationality'] = isset($inputs['nationality']) ? $inputs['nationality'] : 'pakistani';
            // dd($inputs);

            $stakeholder = $this->model()->create($data);

            if (isset($inputs['attachment'])) {
                foreach ($inputs['attachment'] as $attachment) {
                    $stakeholder->addMedia($attachment)->toMediaCollection('stakeholder_cnic');
                }
                $returnValue = changeImageDirectoryPermission();
                Log::info("changeImageDirectoryPermission => " . $returnValue);
            }

            if (isset($inputs['next-of-kins']) && count($inputs['next-of-kins']) > 0) {
                $nextOfKins = [];
                foreach ($inputs['next-of-kins'] as $nok) {
                    if ($nok['stakeholder_id'] != 0) {
                        $data = [
                            'stakeholder_id' => $stakeholder->id,
                            'kin_id' => $nok['stakeholder_id'],
                            'relation' => $nok['relation'],
                            'site_id' => decryptParams($site_id),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $nextOfKins[] =  StakeholderNextOfKin::create($data);

                        StakeholderType::where('stakeholder_id', ($nok['stakeholder_id']))->where('type', 'K')->update([
                            'status' => true,
                        ]);
                    }
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
                        'stakeholder_contact_id' => $contact['stakeholder_contact_id']
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

                if ($inputs['stakeholder_type'] == 'C') {
                    if (in_array($value, ['C', 'L'])) {
                        $stakeholderType['status'] = 1;
                    }
                }

                if ($inputs['parent_id'] > 0) {
                    (new StakeholderType())->where([
                        'stakeholder_id' => $inputs['parent_id'],
                        'type' => 'K',
                    ])->update([
                        'status' => true,
                    ]);
                }

                $stakeholderTypeData[] = $stakeholderType;
            }
            // dd($stakeholderTypeData);
            $stakeholder_type = StakeholderType::insert($stakeholderTypeData);

            //save custom fields

            foreach ($customFields as $key => $value) {
                $stakeholder->CustomFieldValues()->updateOrCreate([
                    'custom_field_id' => $value->id,
                ], [
                    'value' => isset($inputs[$value->slug]) ? $inputs[$value->slug] : null,
                ]);
            }
            return $stakeholder;
        });
    }

    public function update($site_id, $id, $inputs, $customFields)
    {
        DB::transaction(function () use ($site_id, $id, $inputs, $customFields) {
            $stakeholder = $this->model()->find($id);
            $nextOfKinId = $stakeholder->parent_id;
            $cnic = $stakeholder->cnic;


            if ($inputs['stakeholder_as'] == 'i') {
                $data = [
                    'full_name' => $inputs['full_name'],
                    'father_name' => $inputs['father_name'],
                    'occupation' => $inputs['occupation'],
                    'designation' => $inputs['designation'],
                    'cnic' => $inputs['cnic'],
                    'passport_no' => $inputs['passport_no'],
                    'ntn' => $inputs['ntn'],
                    'email' => $inputs['individual_email'],
                    'office_email' => $inputs['office_email'],
                    'mobile_contact' => $inputs['mobile_contact'],
                    'mobileContactCountryDetails' => $inputs['mobileContactCountryDetails'],
                    'office_contact' => $inputs['office_contact'],
                    'OfficeContactCountryDetails' => $inputs['OfficeContactCountryDetails'],
                    'referred_by' => $inputs['referred_by'],
                    'source' => $inputs['source'],
                    'date_of_birth' => $inputs['dob'],
                    'is_local' => $inputs['is_local'],
                    'nationality' => $inputs['nationality'],

                ];
            } else if ($inputs['stakeholder_as'] == 'c') {
                $data = [
                    'full_name' => $inputs['company_name'],
                    'industry' => $inputs['industry'],
                    'office_contact' => $inputs['company_office_contact'],
                    'OfficeContactCountryDetails' => $inputs['CompanyOfficeContactCountryDetails'],
                    'mobile_contact' => $inputs['company_optional_contact'],
                    'mobileContactCountryDetails' => $inputs['companyMobileContactCountryDetails'],
                    'email' => $inputs['company_email'],
                    'office_email' => $inputs['company_office_email'],
                    'website' => $inputs['website'],
                    'parent_company' => $inputs['parent_company'],
                    'cnic' => $inputs['registration'],
                    'strn' => $inputs['strn'],
                    'ntn' => $inputs['company_ntn'],
                    'origin' => $inputs['origin'],
                ];
            }
            $data['stakeholder_as'] = $inputs['stakeholder_as'];
            $data['site_id'] = $site_id;
            // residential address fields
            $data['residential_address_type'] = $inputs['residential_address_type'];
            $data['residential_address'] = $inputs['residential_address'];
            $data['residential_postal_code'] = $inputs['residential_postal_code'];
            $data['residential_country_id'] = isset($inputs['residential_country']) && $inputs['residential_country'] > 0 ? $inputs['residential_country'] : 167;
            $data['residential_state_id'] = isset($inputs['residential_state']) ? $inputs['residential_state'] : 0;
            $data['residential_city_id'] = isset($inputs['residential_city']) ? $inputs['residential_city'] : 0;

            //mailing address fields
            $data['mailing_address_type'] = $inputs['mailing_address_type'];
            $data['mailing_address'] = $inputs['mailing_address'];
            $data['mailing_postal_code'] = $inputs['mailing_postal_code'];
            $data['mailing_country_id'] = isset($inputs['mailing_country']) && $inputs['mailing_country'] > 0 ? $inputs['mailing_country'] : 167;
            $data['mailing_state_id'] = isset($inputs['mailing_state']) ? $inputs['mailing_state'] : 0;
            $data['mailing_city_id'] = isset($inputs['mailing_city']) ? $inputs['mailing_city'] : 0;

            $data['comments'] = $inputs['comments'];

            $data['nationality'] = isset($inputs['nationality']) ? $inputs['nationality'] : 'pakistani';
            // dd($inputs);
            if ($nextOfKinId > 0 && $nextOfKinId != $inputs['parent_id']) {
                $allNextOfKin = $this->model()->where(['parent_id' => $stakeholder->parent_id])->get();


                if (count($allNextOfKin) == 1) {
                    $specificNextOfKin = $this->model()->where(['parent_id' => $nextOfKinId, 'cnic' => $cnic])->first();
                    $nextOFkinStakholder = StakeholderType::where(['stakeholder_id' => $nextOfKinId, 'type' => 'K'])->first()->update(['status' => false]);
                    if ($inputs['parent_id'] > 0) {
                        (new StakeholderType())->where([
                            'stakeholder_id' => $inputs['parent_id'],
                            'type' => 'K',
                        ])->update([
                            'status' => true,
                        ]);
                    }
                }
            } else {
                if ($inputs['parent_id'] > 0) {
                    (new StakeholderType())->where([
                        'stakeholder_id' => $inputs['parent_id'],
                        'type' => 'K',
                    ])->update([
                        'status' => true,
                    ]);
                }
            }

            $stakeholder->update($data);

            $stakeholder->clearMediaCollection('stakeholder_cnic');

            if (isset($inputs['attachment'])) {
                foreach ($inputs['attachment'] as $attachment) {
                    $stakeholder->addMedia($attachment)->toMediaCollection('stakeholder_cnic');
                }
                changeImageDirectoryPermission();
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
            $stakeholder->nextOfKin()->delete();

            if (isset($inputs['next-of-kins']) && count($inputs['next-of-kins']) > 0) {
                $nextOfKins = [];

                foreach ($inputs['next-of-kins'] as $nok) {
                    if ($nok['stakeholder_id'] != 0) {
                        $nextOfKins[] = StakeholderNextOfKin::create([
                            'stakeholder_id' => $stakeholder->id,
                            'kin_id' => $nok['stakeholder_id'],
                            'site_id' => $site_id,
                            'relation' => $nok['relation'],
                        ]);
                        StakeholderType::where('stakeholder_id', ($nok['stakeholder_id']))->where('type', 'K')->update([
                            'status' => true,
                        ]);
                    }
                }
            }

            foreach ($customFields as $key => $value) {
                $stakeholder->CustomFieldValues()->updateOrCreate([
                    'custom_field_id' => $value->id,
                ], [
                    'value' => isset($inputs[$value->slug]) ? $inputs[$value->slug] : null,
                ]);
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
            'stakeholder_contact_id' => 0,
            'optional_contact_number' => '',
            'nationality' => '',
            'mailing_address'  => '',
            'optional_contact'  => '',
            'stakeholder_as' => 'i',
            'email'  => '',
            'optional_email'  => '',
            'country_id' => 0,
            'city_id' => 0,
            'state_id' => 0,
            'countryDetails' => null,
            'OptionalCountryDetails' => null,
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
