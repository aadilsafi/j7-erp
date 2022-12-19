<?php

namespace App\Services\PaymentVoucher;

use App\Models\AccountHead;
use App\Models\Bank;
use App\Models\PaymentVocuher;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use App\Services\PaymentVoucher\paymentInterface;
use DB;
use Exception;
use Illuminate\Support\Str;

class paymentService implements paymentInterface
{

    private $financialTransactionInterface;

    public function __construct(
        FinancialTransactionInterface $financialTransactionInterface
    ) {
        $this->financialTransactionInterface = $financialTransactionInterface;
    }

    public function model()
    {
        return new PaymentVocuher();
    }

    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {

            if ($inputs['bank_id'] == 0) {
                if ($inputs['mode_of_payment'] == 'Cheque' || $inputs['mode_of_payment'] == 'Online') {
                    $bankData = [
                        'site_id' => decryptParams($site_id),
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
                        'site_id' => decryptParams($site_id),
                        'modelable_id' => null,
                        'modelable_type' => null,
                        'code' => $bank->account_number,
                        'name' => $bank->name,
                        'level' => 5,
                    ];
                    $accountHead =  AccountHead::create($acountHeadData);
                }
            }

            $serail_no  = $this->model()::all();
            if (isset($serail_no) && count($serail_no) > 0) {
                $last_data = collect($serail_no)->last();
                $serail_no = (float)$last_data->id + 1;
                $serail_no =  sprintf('%03d', $serail_no);
            } else {
                $serail_no = '001';
            }

            $payment_voucher_data = [
                'site_id' => 1,
                'user_id' => auth()->user()->id,
                "name" => $inputs['name'],
                "identity_number" => $inputs['identity_number'],
                "buiness_address" => $inputs['buiness_address'],
                "ntn" => $inputs['ntn'],
                "tax_status" => $inputs['tax_status'],
                "representative" => $inputs['representative'],
                "business_type" => $inputs['business_type'],
                "description" => $inputs['description'],
                "account_payable" => $inputs['account_payable'],
                "account_number" => $inputs['account_payable'],
                "stakeholder_type" => $inputs['stakeholder_type_id'],
                "total_payable_amount" => str_replace(',', '', $inputs['total_payable_amount']),
                "expense_account" => $inputs['expense_account'],
                "advance_given" =>  str_replace(',', '', $inputs['advance_given']),
                "discount_recevied" => str_replace(',', '', $inputs['discount_recevied']),
                "remaining_payable" => str_replace(',', '', $inputs['remaining_payable']),
                "net_payable" => str_replace(',', '', $inputs['net_payable']),
                "payment_mode" => $inputs['mode_of_payment'],
                "other_value" => $inputs['other_value'],
                "online_instrument_no" => $inputs['online_instrument_no'],
                "transaction_date" => $inputs['transaction_date'],
                "cheque_no" => $inputs['cheque_no'],
                "bank_id" => $inputs['bank_id'],
                "comments" => $inputs['comments'],
                "amount_to_be_paid" => str_replace(',', '', $inputs['amount_to_be_paid']),
                "receiving_date" => now(),
                "serial_no" => $serail_no,
            ];


            if ($inputs['stakeholder_type_id'] == 'C') {
                $payment_voucher_data['customer_id'] = $inputs['stakeholder_id'];
                $payment_voucher_data['customer_dealer_vendor_details'] = $inputs['name'] . ' Customer';
                $payment_voucher_data['customer_ap_account'] = $inputs['account_payable'];
            }
            if ($inputs['stakeholder_type_id'] == 'D') {
                $payment_voucher_data['dealer_id'] = $inputs['stakeholder_id'];
                $payment_voucher_data['customer_dealer_vendor_details'] = $inputs['name'] . ' Dealer';
                $payment_voucher_data['dealer_ap_account'] = $inputs['account_payable'];
            }
            if ($inputs['stakeholder_type_id'] == 'V') {
                $payment_voucher_data['vendor_id'] = $inputs['stakeholder_id'];
                $payment_voucher_data['customer_dealer_vendor_details'] = $inputs['name'] . ' Vendor';
                $payment_voucher_data['vendor_ap_account'] = $inputs['account_payable'];
            }

            $stakeholder_id = $inputs['stakeholder_id'];
            $payment_voucher = $this->model()->create($payment_voucher_data);
            $transaction = $this->financialTransactionInterface->makePaymentVoucherTransaction($payment_voucher, $stakeholder_id);
        });

        return true;
    }
}