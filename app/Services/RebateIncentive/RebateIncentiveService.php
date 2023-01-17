<?php

namespace App\Services\RebateIncentive;

use App\Exceptions\GeneralException;
use App\Models\RebateIncentiveModel;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Models\Unit;
use App\Services\RebateIncentive\RebateIncentiveInterface;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\Rebateincentive\storeRequest;
use App\Models\AccountHead;
use App\Models\Bank;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Utils\Enums\StakeholderTypeEnum;
use Auth;
use Str;

class RebateIncentiveService implements RebateIncentiveInterface
{

    private $financialTransactionInterface, $stakeholderInterface;

    public function __construct(
        FinancialTransactionInterface $financialTransactionInterface,
        StakeholderInterface $stakeholderInterface
    ) {
        $this->financialTransactionInterface = $financialTransactionInterface;
        $this->stakeholderInterface = $stakeholderInterface;
    }




    public function model()
    {
        return new RebateIncentiveModel();
    }

    // Get
    public function getByAll($site_id)
    {
        return $this->model()->whereSiteId($site_id)->get();
    }

    public function getById($site_id, $id)
    {

        return $this->model()->find($id);
    }

    // Store
    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {

            $individual = $inputs['individual'];

            $company = $inputs['company'];

            if (isset($individual['cnic']) &&  $inputs['dealer_id'] == 0 && $inputs['stakeholder_as'] == 'i' && $this->stakeholderInterface->model()->where('cnic', $individual['cnic'])->exists()) {
                throw new GeneralException('Stakeholder CNIC already exists');
            }
            if (isset($company['cnic']) &&  $inputs['dealer_id'] == 0 && $inputs['stakeholder_as'] == 'c' && $this->stakeholderInterface->model()->where('cnic', $company['cnic'])->exists()) {
                throw new GeneralException('Company Registration No already exists');
            }


            if (!isset($inputs['stakeholder_as'])) {
                $leadStakeholder = Stakeholder::find($inputs['dealer_id']);
                $stakeholder_as = $leadStakeholder->stakeholder_as;
            } else {
                $stakeholder_as = $inputs['stakeholder_as'];
            }
            if ($stakeholder_as == 'i') {
                $stakeholderData = [
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
                    'source' => $individual['source'] ?? 0,
                    'date_of_birth' => $individual['dob'],
                    'is_local' => isset($individual['is_local']) ? $individual['is_local'] : 0,
                    'nationality' => $individual['nationality'],
                ];
            } else if ($stakeholder_as == 'c') {
                $stakeholderData = [
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
            $stakeholderData['stakeholder_as'] = $stakeholder_as;
            $stakeholderData['site_id'] = $site_id;

            // residential address fields
            $residential = $inputs['residential'];
            $stakeholderData['residential_address_type'] = $residential['address_type'];
            $stakeholderData['residential_address'] = $residential['address'];
            $stakeholderData['residential_postal_code'] = $residential['postal_code'];
            $stakeholderData['residential_country_id'] = $residential['country'] > 0 ? $residential['country'] : 167;
            $stakeholderData['residential_state_id'] =  $residential['state'];
            $stakeholderData['residential_city_id'] =  $residential['city'];

            //mailing address fields
            $mailing = $inputs['mailing'];
            $stakeholderData['mailing_address_type'] = $mailing['address_type'];
            $stakeholderData['mailing_address'] = $mailing['address'];
            $stakeholderData['mailing_postal_code'] = $mailing['postal_code'];
            $stakeholderData['mailing_country_id'] = $mailing['country'] > 0 ? $mailing['country'] : 167;
            $stakeholderData['mailing_state_id'] = $mailing['state'];
            $stakeholderData['mailing_city_id'] = $mailing['city'];

            $stakeholderData['comments'] = $inputs['comments'];

            $stakeholder = $this->stakeholderInterface->model()->updateOrCreate([
                'id' =>$inputs['dealer_id'],
            ], $stakeholderData);

            if ($inputs['dealer_id'] == 0 || $inputs['dealer_id'] == null) {
                $stakeholderTypeCode = Str::of($stakeholder->id)->padLeft(3, '0');
                $stakeholderTypeData  = [
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::CUSTOMER->value,
                        'stakeholder_code' => StakeholderTypeEnum::CUSTOMER->value . '-' . $stakeholderTypeCode,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::VENDOR->value,
                        'stakeholder_code' => StakeholderTypeEnum::VENDOR->value . '-' . $stakeholderTypeCode,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::DEALER->value,
                        'stakeholder_code' => StakeholderTypeEnum::DEALER->value . '-' . $stakeholderTypeCode,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::NEXT_OF_KIN->value,
                        'stakeholder_code' => StakeholderTypeEnum::NEXT_OF_KIN->value . '-' . $stakeholderTypeCode,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::LEAD->value,
                        'stakeholder_code' => StakeholderTypeEnum::LEAD->value . '-' . $stakeholderTypeCode,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::INVESTOR->value,
                        'stakeholder_code' => StakeholderTypeEnum::INVESTOR->value . '-' . $stakeholderTypeCode,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ];
                $stakeholderType = (new StakeholderType())->insert($stakeholderTypeData);
            }


            $serail_no = $this->model()::max('id') + 1;
            $serail_no =  sprintf('%03d', $serail_no);
            $rebatedata = [
                'user_id'=> Auth::user()->id,
                'site_id' => $site_id,
                'unit_id' => $inputs['unit_id'],
                'doc_no' => $inputs['doc_number'],
                'stakeholder_id' => $inputs['stakeholder_id'],
                'stakeholder_data' => json_encode(Stakeholder::find($inputs['stakeholder_id'])),
                'unit_data' => json_encode(Unit::find($inputs['unit_id'])),
                'deal_type' => $inputs['deal_type'],
                'commision_percentage' => $inputs['rebate_percentage'],
                'commision_total' => $inputs['rebate_total'],
                'status' => 0,
                'comments' => $inputs['comments'],
                'dealer_id' => $stakeholder->id,
                'is_for_dealer_incentive' => true,
                // 'bank_id' => $inputs['bank_id'],
                // 'mode_of_payment' => $inputs['mode_of_payment'],
                // 'other_value' => $inputs['other_value'],
                // 'cheque_no' => $inputs['cheque_no'],
                // 'online_instrument_no' => $inputs['online_instrument_no'],
                // 'transaction_date' => $inputs['transaction_date'],
                'serial_no' => 'RI-' . $serail_no,
            ];
            $rebate = $this->model()->create($rebatedata);

            $unit = Unit::find($inputs['unit_id']);
            $unit->is_for_rebate = false;
            $unit->update();

            // $transaction = $this->financialTransactionInterface->makeRebateIncentiveTransaction($rebate->id);
            return $rebate;
        });
    }

    public function update($site_id, $id, $inputs)
    {
        DB::transaction(function () use ($site_id, $id, $inputs) {

            $dealer_id = $inputs['dealer_id'];
            if ($dealer_id == 0) {
                $dealer_data = $inputs['dealer'];

                $dealer = Stakeholder::create([
                    'site_id' => $site_id,
                    'full_name' => $dealer_data['full_name'],
                    'father_name' => $dealer_data['father_name'],
                    'occupation' => $dealer_data['occupation'],
                    'designation' => $dealer_data['designation'],
                    'cnic' => $dealer_data['cnic'],
                    'ntn' => $dealer_data['ntn'],
                    'contact' => $dealer_data['contact'],
                    'address' => $dealer_data['address'],
                    'comments' => $dealer_data['comments'],
                ]);

                $stakeholdertype = [
                    [
                        'stakeholder_id' => $dealer->id,
                        'type' => 'C',
                        'stakeholder_code' => 'C-00' . $dealer->id,
                        'status' => 0,
                    ],
                    [
                        'stakeholder_id' => $dealer->id,
                        'type' => 'V',
                        'stakeholder_code' => 'V-00' . $dealer->id,
                        'status' => 0,
                    ],
                    [
                        'stakeholder_id' => $dealer->id,
                        'type' => 'D',
                        'stakeholder_code' => 'D-00' . $dealer->id,
                        'status' => 1,
                    ],
                    [
                        'stakeholder_id' => $dealer->id,
                        'type' => 'K',
                        'stakeholder_code' => 'K-00' . $dealer->id,
                        'status' => 0,
                    ],
                    [
                        'stakeholder_id' => $dealer->id,
                        'type' => 'L',
                        'stakeholder_code' => 'L-00' . $dealer->id,
                        'status' => 0,
                    ]
                ];

                $stakeholder_type = StakeholderType::insert($stakeholdertype);

                $dealer_id = $dealer->id;
            }

            $rebatedata = [
                'site_id' => $site_id,
                'stakeholder_id' => $inputs['stakeholder_id'],
                'stakeholder_data' => json_encode(Stakeholder::find($inputs['stakeholder_id'])),
                'deal_type' => $inputs['deal_type'],
                'commision_percentage' => $inputs['rebate_percentage'],
                'commision_total' => $inputs['rebate_total'],
                'status' => 0,
                'comments' => $inputs['comments'],
                'dealer_id' => $dealer_id,
            ];

            $rebate = $this->model()->where('id', $id)->update($rebatedata);

            return $rebate;
        });
    }

    public function destroy($site_id, $inputs)
    {
        $this->model()->whereIn('id', $inputs)->delete();

        return true;
    }
}
