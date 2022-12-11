<?php

namespace App\Services\RebateIncentive;

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
use Str;

class RebateIncentiveService implements RebateIncentiveInterface
{

    private $financialTransactionInterface;

    public function __construct(
        FinancialTransactionInterface $financialTransactionInterface
    ) {
        $this->financialTransactionInterface = $financialTransactionInterface;
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
                    'countryDetails' => $dealer_data['countryDetails'],
                    'optional_contact' => $dealer_data['optional_contact'],
                    'OptionalCountryDetails' => $dealer_data['OptionalCountryDetails'],
                    'mailing_address' => $dealer_data['mailing_address'],
                    'email' => $dealer_data['email'],
                    'optional_email' => $dealer_data['optional_email'],
                    'country_id' => $dealer_data['country_id'],
                    'state_id' => $dealer_data['state_id'],
                    'city_id' => $dealer_data['city_id'],
                    'stakeholder_as' => 'i'
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
            if (!isset($inputs['bank_name'])) {
                $inputs['bank_id'] =  null;
                $inputs['bank_name'] = null;
            }

            if ($inputs['bank_id'] == 0) {

                if ($inputs['mode_of_payment'] == 'Cheque' || $inputs['mode_of_payment'] == 'Online') {
                    $bankData = [
                        'site_id' => $site_id,
                        'name' => $inputs['bank_name'],
                        'slug' => Str::slug($inputs['bank_name']),
                        'account_number' => $inputs['bank_account_number'],
                        'branch' => $inputs['bank_branch'],
                        'branch_code' => $inputs['bank_branch_code'],
                        'address' => $inputs['bank_address'],
                        'contact_number' => $inputs['bank_contact_number'],
                        'status' => true,
                        'comments' => $inputs['bank_comments'],
                    ];
                    $bank = Bank::create($bankData);
                    $inputs['bank_id'] = $bank->id;
                    $inputs['bank_name'] = $bank->name;
                    // added in accound heads
                    $acountHeadData = [
                        'site_id' => $site_id,
                        'modelable_id' => null,
                        'modelable_type' => null,
                        'code' => $bank->account_number,
                        'name' => $bank->name,
                        'level' => 5,
                    ];
                    $accountHead =  AccountHead::create($acountHeadData);
                }
            }


            $rebatedata = [
                'site_id' => $site_id,
                'unit_id' => $inputs['unit_id'],
                'stakeholder_id' => $inputs['stakeholder_id'],
                'stakeholder_data' => json_encode(Stakeholder::find($inputs['stakeholder_id'])),
                'unit_data' => json_encode(Unit::find($inputs['unit_id'])),
                'deal_type' => $inputs['deal_type'],
                'commision_percentage' => $inputs['rebate_percentage'],
                'commision_total' => $inputs['rebate_total'],
                'status' => 0,
                'comments' => $inputs['comments'],
                'dealer_id' => $dealer_id,
                'is_for_dealer_incentive' => true,
                'bank_id' => $inputs['bank_id'],
                'mode_of_payment' => $inputs['mode_of_payment'],
                'other_value' => $inputs['other_value'],
                'cheque_no' => $inputs['cheque_no'],
                'online_instrument_no' => $inputs['online_instrument_no'],
                'transaction_date' => $inputs['transaction_date'],
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
