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
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use Illuminate\Support\Facades\Storage;
use App\Utils\Enums\StakeholderTypeEnum;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StakeholderService implements StakeholderInterface
{

    private $financialTransactionInterface;

    public function __construct(
        FinancialTransactionInterface $financialTransactionInterface
    ) {
        $this->financialTransactionInterface = $financialTransactionInterface;
    }

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
            $individual = $inputs['individual'];
            $company = $inputs['company'];

            if ($inputs['stakeholder_as'] == 'i') {
                $data = [
                    'full_name' => $individual['full_name'],
                    'father_name' => $individual['father_name'],
                    'occupation' => $individual['occupation'],
                    'designation' => $individual['designation'],
                    'cnic' => $individual['cnic'],
                    'passport_no' => $individual['passport_no'],
                    'ntn' => $individual['ntn'],
                    'email' => $individual['individual_email'],
                    'office_email' => $individual['office_email'],
                    'mobile_contact' => $individual['mobile_contact'],
                    'mobileContactCountryDetails' => $inputs['mobileContactCountryDetails'],
                    'office_contact' => $individual['office_contact'],
                    'OfficeContactCountryDetails' => $inputs['OfficeContactCountryDetails'],
                    'referred_by' => $individual['referred_by'],
                    'source' => $individual['source'],
                    'date_of_birth' => $individual['dob'],
                    'is_local' => isset($individual['is_local']) ? $individual['is_local'] : 0,
                    'nationality' => $individual['nationality'],
                ];
            } else if ($inputs['stakeholder_as'] == 'c') {
                $data = [
                    'full_name' => $company['company_name'],
                    'industry' => $company['industry'],
                    'office_contact' => $company['company_office_contact'],
                    'OfficeContactCountryDetails' => $inputs['CompanyOfficeContactCountryDetails'],
                    'mobile_contact' => $company['company_optional_contact'],
                    'mobileContactCountryDetails' => $inputs['companyMobileContactCountryDetails'],
                    'email' => $company['company_email'],
                    'office_email' => $company['company_office_email'],
                    'website' => $company['website'],
                    'parent_company' => $company['parent_company'],
                    'cnic' => $company['registration'],
                    'strn' => $company['strn'],
                    'ntn' => $company['company_ntn'],
                    'origin' => $company['origin'],
                ];
            }
            $data['stakeholder_as'] = $inputs['stakeholder_as'];
            $data['site_id'] = decryptParams($site_id);

            // residential address fields
            $residential = $inputs['residential'];
            $data['residential_address_type'] = $residential['address_type'];
            $data['residential_address'] = $residential['address'];
            $data['residential_postal_code'] = $residential['postal_code'];
            $data['residential_country_id'] = $residential['country'] > 0 ? $residential['country'] : 167;
            $data['residential_state_id'] =  $residential['state'];
            $data['residential_city_id'] =  $residential['city'];

            //mailing address fields
            $mailing = $inputs['mailing'];
            $data['mailing_address_type'] = $mailing['address_type'];
            $data['mailing_address'] = $mailing['address'];
            $data['mailing_postal_code'] = $mailing['postal_code'];
            $data['mailing_country_id'] = $mailing['country'] > 0 ? $mailing['country'] : 167;
            $data['mailing_state_id'] = $mailing['state'];
            $data['mailing_city_id'] = $mailing['city'];

            $data['comments'] = $inputs['comments'];

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
                    if (isset($nok['stakeholder_id']) && $nok['stakeholder_id'] != 0) {
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
                        StakeholderType::where('stakeholder_id', ($nok['stakeholder_id']))->where('type', 'L')->update([
                            'status' => true,
                        ]);
                    }
                }
            }

            if (isset($inputs['stakeholder_type']) && $inputs['stakeholder_type'] == 'K') {
                if (isset($inputs['stakeholders']) && count($inputs['stakeholders']) > 0) {
                    $stakeholders = [];
                    foreach ($inputs['stakeholders'] as $nok) {
                        if ($nok['stakeholder_id'] != 0) {
                            $data = [
                                'stakeholder_id' => $nok['stakeholder_id'],
                                'kin_id' => $stakeholder->id,
                                'relation' => $nok['relation'],
                                'site_id' => decryptParams($site_id),
                                'created_at' => now(),
                                'updated_at' => now(),
                            ];

                            $nextOfKins[] =  StakeholderNextOfKin::create($data);
                        }
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

                if (in_array($value, ['L'])) {
                    $stakeholderType['status'] = 1;
                }


                $stakeholderTypeData[] = $stakeholderType;
            }
            // dd($stakeholderTypeData);
            $stakeholder_type = StakeholderType::insert($stakeholderTypeData);
            if ($inputs['stakeholder_type'] == 'V') {
                $vendor_ap_account = $this->financialTransactionInterface->makeVendorApAccount($stakeholder->id);
            }
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
            $cnic = $stakeholder->cnic;

            $individual = $inputs['individual'];
            $company = $inputs['company'];

            if ($inputs['stakeholder_as'] == 'i') {
                $data = [
                    'full_name' => $individual['full_name'],
                    'father_name' => $individual['father_name'],
                    'occupation' => $individual['occupation'],
                    'designation' => $individual['designation'],
                    'cnic' => $individual['cnic'],
                    'passport_no' => $individual['passport_no'],
                    'ntn' => $individual['ntn'],
                    'email' => $individual['individual_email'],
                    'office_email' => $individual['office_email'],
                    'mobile_contact' => $individual['mobile_contact'],
                    'mobileContactCountryDetails' => $inputs['mobileContactCountryDetails'],
                    'office_contact' => $individual['office_contact'],
                    'OfficeContactCountryDetails' => $inputs['OfficeContactCountryDetails'],
                    'referred_by' => $individual['referred_by'],
                    'source' => $individual['source'],
                    'date_of_birth' => $individual['dob'],
                    'is_local' => isset($individual['is_local']) ? $individual['is_local'] : 0,
                    'nationality' => $individual['nationality'],
                ];
            } else if ($inputs['stakeholder_as'] == 'c') {
                $data = [
                    'full_name' => $company['company_name'],
                    'industry' => $company['industry'],
                    'office_contact' => $company['company_office_contact'],
                    'OfficeContactCountryDetails' => $inputs['CompanyOfficeContactCountryDetails'],
                    'mobile_contact' => $company['company_optional_contact'],
                    'mobileContactCountryDetails' => $inputs['companyMobileContactCountryDetails'],
                    'email' => $company['company_email'],
                    'office_email' => $company['company_office_email'],
                    'website' => $company['website'],
                    'parent_company' => $company['parent_company'],
                    'cnic' => $company['registration'],
                    'strn' => $company['strn'],
                    'ntn' => $company['company_ntn'],
                    'origin' => $company['origin'],
                ];
            }
            $data['stakeholder_as'] = $inputs['stakeholder_as'];
            $data['site_id'] = $site_id;

            // residential address fields
            $residential = $inputs['residential'];
            $data['residential_address_type'] = $residential['address_type'];
            $data['residential_address'] = $residential['address'];
            $data['residential_postal_code'] = $residential['postal_code'];
            $data['residential_country_id'] = $residential['country'] > 0 ? $residential['country'] : 167;
            $data['residential_state_id'] =  $residential['state'];
            $data['residential_city_id'] =  $residential['city'];

            //mailing address fields
            $mailing = $inputs['mailing'];
            $data['mailing_address_type'] = $mailing['address_type'];
            $data['mailing_address'] = $mailing['address'];
            $data['mailing_postal_code'] = $mailing['postal_code'];
            $data['mailing_country_id'] = $mailing['country'] > 0 ? $mailing['country'] : 167;
            $data['mailing_state_id'] = $mailing['state'];
            $data['mailing_city_id'] = $mailing['city'];

            $data['comments'] = $inputs['comments'];

            // dd($inputs);

            $stakeholder->update($data);

            $stakeholder->clearMediaCollection('stakeholder_cnic');

            if (isset($inputs['attachment'])) {
                foreach ($inputs['attachment'] as $attachment) {
                    $stakeholder->addMedia($attachment)->toMediaCollection('stakeholder_cnic');
                }
                changeImageDirectoryPermission();
            }

            $stakeholder->nextOfKin()->delete();

            if (isset($inputs['next-of-kins']) && count($inputs['next-of-kins']) > 0) {
                $nextOfKins = [];

                foreach ($inputs['next-of-kins'] as $nok) {
                    if (isset($nok['stakeholder_id']) && $nok['stakeholder_id'] != 0) {
                        $nextOfKins[] = StakeholderNextOfKin::create([
                            'stakeholder_id' => $stakeholder->id,
                            'kin_id' => $nok['stakeholder_id'],
                            'site_id' => $site_id,
                            'relation' => $nok['relation'],
                        ]);
                        StakeholderType::where('stakeholder_id', ($nok['stakeholder_id']))->where('type', 'K')->update([
                            'status' => true,
                        ]);
                        StakeholderType::where('stakeholder_id', ($nok['stakeholder_id']))->where('type', 'L')->update([
                            'status' => true,
                        ]);
                    }
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

                    if ($key == 'V') {
                        $vendor_ap_account = $this->financialTransactionInterface->makeVendorApAccount($stakeholder->id);
                    }
                }
            }

            $stakeholder->KinStakeholders()->detach();
            if (isset($inputs['stakeholders']) && count($inputs['stakeholders']) > 0) {
                $stakeholders = [];

                foreach ($inputs['stakeholders'] as $nok) {
                    if (isset($nok['stakeholder_id']) && $nok['stakeholder_id'] != 0) {
                        $data = [
                            'stakeholder_id' => $nok['stakeholder_id'],
                            'kin_id' => $stakeholder->id,
                            'relation' => $nok['relation'],
                            'site_id' => $site_id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                        $nextOfKins[] =  StakeholderNextOfKin::create($data);
                    }
                }
            }

            $stakeholder->contacts()->delete();
            if (isset($inputs['contact-persons']) && count($inputs['contact-persons']) > 0) {
                $contacts = [];
                foreach ($inputs['contact-persons'] as $contact) {
                    $contacts[] = new StakeholderContact($contact);
                }
                $stakeholder->contacts()->saveMany($contacts);
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
