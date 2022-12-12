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
                ];
            } else if ($inputs['stakeholder_as'] == 'c') {
                $data = [
                    'full_name' => $inputs['company_name'],
                    'occupation' => $inputs['industry'],
                    'cnic' => $inputs['registration'],
                ];
            }
            $data['stakeholder_as'] = $inputs['stakeholder_as'];
            $data['site_id'] = decryptParams($site_id);
            $data['ntn'] = $inputs['ntn'];
            $data['email'] = $inputs['email'];
            $data['optional_email'] = $inputs['optional_email'];
            $data['contact'] = $inputs['contact'];
            $data['countryDetails'] = $inputs['countryDetails'];
            $data['optional_contact'] = $inputs['optional_contact'];
            $data['OptionalCountryDetails'] = $inputs['OptionalCountryDetails'];
            $data['address'] = $inputs['address'];
            $data['mailing_address'] = $inputs['mailing_address'];
            $data['comments'] = $inputs['comments'];
            $data['country_id'] = isset($inputs['country_id']) && $inputs['country_id'] > 0 ? $inputs['country_id'] : 167;
            $data['state_id'] = isset($inputs['state_id']) ? $inputs['state_id'] : 0;
            $data['city_id'] = isset($inputs['city_id']) ? $inputs['city_id'] : 0;
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
                        'stakeholder_contact_id' => $contact['stackholder_id']
                    ];

                    $contacts[] = new StakeholderContact($data);
                }
                $stakeholder->contacts()->saveMany($contacts);
            }

            // customer ar code 1020201001 for customer 1 receivable Customer Code
            // customer ap code 2020101001 for customer 1 payable Customer Code

            // $customerStakeholderType = StakeholderType::where('type','C')->get();
            // $lastExistedCustomerCode = collect($customerStakeholderType)->last();

            // // set payable customer code
            // $payableCustomerCode = 0;    // payable customer code

            // if(isset($lastExistedCustomerCode->payable_account)){
            //     $payableCustomerCode = $lastExistedCustomerCode->payable_account + 1;
            // }
            // else{
            //     $payableCustomerCode = 2020101003;
            // }

            // // set receivable customer code
            // $receivableCustomerCode = 0;    // receivable customer code

            // if(isset($lastExistedCustomerCode->receivable_account)){
            //     $receivableCustomerCode = $lastExistedCustomerCode->receivable_account + 1;
            // }
            // else{
            //     $receivableCustomerCode = 1020201003;
            // }


            // // Vendor only payable code
            // // vendor ap code 2020103001 for vendor 1 payable vendor code
            // $vendorStakeholderType = StakeholderType::where('type','V')->get();
            // $lastExistedVendorCode = collect($vendorStakeholderType)->last();

            // $payableVendorCode = 0;

            // if(isset($lastExistedVendorCode->payable_account)){
            //     $payableVendorCode = $lastExistedVendorCode->payable_account + 1;
            // }
            // else{
            //     $payableVendorCode = 2020103003;
            // }

            //  // Dealer only payable code
            // // dealer ap code 2020103001 for dealer 1 payable vendor code
            // $dealerStakeholderType = StakeholderType::where('type','D')->get();
            // $lastExistedDealerCode = collect($dealerStakeholderType)->last();

            // $payableDealerCode = 0;

            // if(isset($lastExistedDealerCode->payable_account)){
            //     $payableDealerCode = $lastExistedDealerCode->payable_account + 1;
            // }
            // else{
            //     $payableDealerCode = 2020102003;
            // }

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

                //Add  Customer Account codes
                // if($value == 'C'){
                //     $stakeholderType['receivable_account'] = $receivableCustomerCode;
                //     $stakeholderType['payable_account'] = $payableCustomerCode;
                // }

                // //Add  Vendor Account codes
                // if($value == 'V'){
                //     $stakeholderType['payable_account'] = $payableVendorCode;
                // }

                // //Add  Dealer Account codes
                // if($value == 'D'){
                //     $stakeholderType['payable_account'] = $payableDealerCode;
                // }

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
                ];
            } else if ($inputs['stakeholder_as'] == 'c') {
                $data = [
                    'full_name' => $inputs['company_name'],
                    'occupation' => $inputs['industry'],
                    'cnic' => $inputs['registration'],
                ];
            }
            $data['stakeholder_as'] = $inputs['stakeholder_as'];
            $data['site_id'] = $site_id;
            $data['ntn'] = $inputs['ntn'];
            $data['email'] = $inputs['email'];
            $data['optional_email'] = $inputs['optional_email'];
            $data['contact'] = $inputs['contact'];
            $data['countryDetails'] = $inputs['countryDetails'];
            $data['optional_contact'] = $inputs['optional_contact'];
            $data['OptionalCountryDetails'] = $inputs['OptionalCountryDetails'];
            $data['address'] = $inputs['address'];
            $data['mailing_address'] = $inputs['mailing_address'];
            $data['comments'] = $inputs['comments'];
            $data['country_id'] = isset($inputs['country_id']) && $inputs['country_id'] > 0 ? $inputs['country_id'] : 167;
            $data['state_id'] = isset($inputs['state_id']) ? $inputs['state_id'] : 0;
            $data['city_id'] = isset($inputs['city_id']) ? $inputs['city_id'] : 0;
            $data['nationality'] = isset($inputs['nationality']) ? $inputs['nationality'] : 'pakistani';

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
