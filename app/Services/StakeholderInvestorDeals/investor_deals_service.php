<?php

namespace App\Services\StakeholderInvestorDeals;

use App\Exceptions\GeneralException;
use App\Models\Stakeholder;
use App\Models\StakeholderInvestor;
use App\Models\StakeholderInvestorDeal;
use App\Models\StakeholderType;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Services\StakeholderInvestorDeals\investor_deals_interface;
use App\Utils\Enums\StakeholderTypeEnum;
use Auth;
use DB;
use Exception;
use Illuminate\Support\Str;

class investor_deals_service implements investor_deals_interface
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
        return new StakeholderInvestor();
    }

    // Store
    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {
            $individual = $inputs['individual'];

            $company = $inputs['company'];

            if (isset($individual['cnic']) &&  $inputs['investor_id'] == null && $inputs['stakeholder_as'] == 'i' && $this->stakeholderInterface->model()->where('cnic', $individual['cnic'])->exists()) {
                throw new GeneralException('Stakeholder CNIC already exists');
            }
            if (isset($company['cnic']) &&  $inputs['investor_id'] == null && $inputs['stakeholder_as'] == 'c' && $this->stakeholderInterface->model()->where('cnic', $company['cnic'])->exists()) {
                throw new GeneralException('Company Registration No already exists');
            }


            if (!isset($inputs['stakeholder_as'])) {
                $leadStakeholder = Stakeholder::find($inputs['investor_id']);
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
                'id' => $inputs['investor_id'],
            ], $stakeholderData);

            if ($inputs['investor_id'] == 0 || $inputs['investor_id'] == null) {
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
                        'status' => 0,
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
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ];
                $stakeholderType = (new StakeholderType())->insert($stakeholderTypeData);
            }

            $serail_no = $this->model()::max('id') + 1;
            $serail_no =  sprintf('%03d', $serail_no);

            $unit_deals = $inputs['unit-deals'];
            $total_received_amount = 0.0;
            foreach ($unit_deals as $unit_deal) {
                $total_received_amount = (float)$total_received_amount + (float)str_replace(',', '', $unit_deal['received_amount']);
            }

            $investor_data = [
                'site_id' => $site_id,
                'user_id' => Auth::user()->id,
                'investor_id' => $stakeholder->id,
                'serial_number' => 'ID-' . $serail_no,
                'doc_no' => $inputs['doc_number'],
                'total_received_amount' => $total_received_amount,
                'total_payable_amount' => 0.0,
                'created_date' => $inputs['created_date']. date(' H:i:s'),
                'status' => 'pending',
                'deal_status' => 'open',
                'paid_status' => 0,
            ];

            $stakeholder_investor = $this->model()->create($investor_data);

            if (isset($inputs['attachment'])&& count($inputs['attachment']) > 0) {
                for ($j = 0; $j < count($inputs['attachment']); $j++) {
                    $stakeholder_investor->addMedia($inputs['attachment'][$j])->toMediaCollection('investor_deal_receivable_attachments');
                    changeImageDirectoryPermission();
                }
            }

            foreach ($unit_deals as $unit_deal_data) {
                $investor_deal = new StakeholderInvestorDeal();
                $investor_deal->site_id = $site_id;
                $investor_deal->stakeholder_investor_id = $site_id;
                $investor_deal->site_id = $site_id;
                $investor_deal->site_id = $stakeholder_investor->id;
                $investor_deal->unit_id = $unit_deal_data['unit'];
                $investor_deal->received_amount = $unit_deal_data['received_amount'];
                $investor_deal->remarks = $unit_deal_data['remarks'];
                $investor_deal->status = 'active';
                $investor_deal->created_date = $inputs['created_date']. date(' H:i:s');
                $investor_deal->save();
            }

        });
        return true;
    }
}