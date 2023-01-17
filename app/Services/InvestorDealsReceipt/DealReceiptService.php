<?php

namespace App\Services\InvestorDealsReceipt;

use App\Models\AccountHead;
use App\Models\Bank;
use App\Models\InvsetorDealsReceipt;
use App\Models\StakeholderInvestor;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use Auth;
use DB;
use Str;

class DealReceiptService implements DealReceiptInterface
{

    private $financialTransactionInterface;

    public function __construct(FinancialTransactionInterface $financialTransactionInterface)
    {
        $this->financialTransactionInterface = $financialTransactionInterface;
    }

    public function model()
    {
        return new InvsetorDealsReceipt();
    }

    // Store
    public function store($site_id, $inputs)
    {
        // DB::transaction(function () use ($site_id, $inputs) {
            $data = $inputs;

            if (!isset($data['bank_name'])) {
                $data['bank_id'] =  null;
                $data['bank_name'] = null;
            }

            if ($data['bank_id'] == 0) {

                if ($data['mode_of_payment'] == 'Cheque' || $data['mode_of_payment'] == 'Online') {

                    $bank_last_account_head = Bank::get();
                    $bank_last_account_head_code = collect($bank_last_account_head)->last()->account_head_code;
                    $bank_starting_code = '10209010001010';

                    if ((float)$bank_last_account_head_code >= (float)$bank_starting_code) {
                        $account_head_code = (float)$bank_last_account_head_code + 1;
                    } else {
                        $account_head_code =  (float)$bank_starting_code + 1;
                    }

                    $bankData = [
                        'site_id' => decryptParams($site_id),
                        'name' => $data['bank_name'],
                        'slug' => Str::slug($data['bank_name']),
                        'account_number' => $data['bank_account_number'],
                        'branch' => $data['bank_branch'],
                        'branch_code' => $data['bank_branch_code'],
                        'address' => $data['bank_address'],
                        'contact_number' => $data['bank_contact_number'],
                        'status' => true,
                        'comments' => $data['bank_comments'],
                        'account_head_code' => $account_head_code,
                    ];
                    $bank = Bank::create($bankData);
                    $data['bank_id'] = $bank->id;
                    $data['bank_name'] = $bank->name;
                    // added in accound heads
                    $acountHeadData = [
                        'site_id' => decryptParams($site_id),
                        'modelable_id' => null,
                        'modelable_type' => null,
                        'code' => $bank->account_head_code,
                        'name' => $bank->name,
                        'account_type' => 'debit',
                        'level' => 5,
                    ];
                    $accountHead =  AccountHead::create($acountHeadData);
                }
            }

            $deal = StakeholderInvestor::find($data['deal_id']);

            $serail_no = $this->model()::max('id') + 1;
            $serail_no =  sprintf('%03d', $serail_no);

            if ($data['mode_of_payment'] == 'Cheque') {
                $status = "inactive";
            } else {
                $status = "active";
            }

            $receipt_data = [
                'site_id' => $site_id,
                'user_id' => Auth::user()->id,
                'investor_deal_id' => $data['deal_id'],
                'bank_id' => $data['bank_id'],
                'investor_id' => $deal->investor_id,
                'serial_number' => 'IDR-' . $serail_no,
                'doc_no' => $data['doc_number'],
                'total_received_amount' => $deal->total_received_amount,
                'created_date' => $data['created_date'] . date(' H:i:s'),
                'status' => $status,
                // 'remarks'=>$data['coments'],
                'name' => $deal->investor->full_name,
                'cnic' => $deal->investor->cnic,
                'phone_no' => $deal->investor->mobile_contact,
                'mode_of_payment' => $data['mode_of_payment'],
                'other_value' => $data['other_value'],
                'cheque_no' => $data['cheque_no'],
                'online_instrument_no' => $data['online_instrument_no'],
                'transaction_date' => $data['transaction_date'],
            ];
            $receipt = $this->model()->create($receipt_data);

            if (isset($data['attachment']) && count($data['attachment']) > 0) {
                foreach ($data['attachment'] as $attachment) {
                    $receipt->addMedia($attachment)->toMediaCollection('investor_deal_receipt_attachment');
                    changeImageDirectoryPermission();
                }
            }

            $transaction = $this->financialTransactionInterface->makeInvestorDealReceivableReceiptTransaction($receipt->id);
            // $deal->paid_status = 1;
            // $deal->update();
        // });
        return true;
    }
}
