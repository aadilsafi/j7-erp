<?php

namespace App\Services\TransferFileReceipts;

use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\Bank;
use App\Models\FileTitleTransfer;
use App\Models\Unit;
use App\Models\Receipt;
use App\Models\ReceiptDraftModel;
use App\Models\SalesPlan;
use App\Models\Stakeholder;
use App\Models\UnitStakeholder;
use App\Models\SiteConfigration;
use App\Models\SalesPlanInstallments;
use App\Models\StakeholderType;
use App\Models\TempReceipt;
use App\Models\TransferReceipt;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use Illuminate\Support\Facades\{URL, Auth, DB, Notification};
use Str;

class TransferFileReceiptService implements TransferFileReceiptInterface
{

    private $financialTransactionInterface;

    public function __construct(
        FinancialTransactionInterface $financialTransactionInterface
    ) {
        $this->financialTransactionInterface = $financialTransactionInterface;
    }

    public function model()
    {
        return new TransferReceipt();
    }

    // Store
    public function store($site_id, $requested_data)
    {
        DB::transaction(function () use ($site_id, $requested_data) {
            $data = $requested_data;

            $transferFile = FileTitleTransfer::find($data['transfer_file_id']);

            if (!isset($data['bank_name'])) {
                $data['bank_id'] =  null;
                $data['bank_name'] = null;
            }

            if ($data['bank_id'] == 0) {

                if ($data['mode_of_payment'] == 'Cheque' || $data['mode_of_payment'] == 'Online') {
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
                    ];
                    $bank = Bank::create($bankData);
                    $data['bank_id'] = $bank->id;
                    $data['bank_name'] = $bank->name;
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

            $max = TransferReceipt::max('id') + 1;

            $receiptData = [
                'site_id' => decryptParams($site_id),
                'unit_id'  => $transferFile->unit_id,
                'sales_plan_id'  => $transferFile->sales_plan_id,
                'file_id'  => $transferFile->file_id,
                'file_title_transfer_id'  => $transferFile->id,
                'stakeholder_id' => $transferFile->transfer_person_id,
                'mode_of_payment' => $data['mode_of_payment'],
                'other_value' => $data['other_value'],
                'cheque_no' => $data['cheque_no'],
                'online_transaction_no' => $data['online_instrument_no'],
                'transaction_date' => $data['transaction_date'],
                'amount_in_words' => numberToWords($transferFile->amount_to_be_paid),
                'amount_in_numbers' => $transferFile->amount_to_be_paid,
                'amount' => $transferFile->amount_to_be_paid,
                'comments' => $data['comments'],
                'status' => ($data['mode_of_payment'] != 'Cheque') ? 1 : 0,
                'bank_details' => $data['bank_name'],
                'bank_id' => $data['bank_id'],
                'created_date' => $requested_data['created_date'] . date(' H:i:s'),
                'customer_ap_amount' => $data['customer_ap_amount'],
                'dealer_ap_amount' => $data['dealer_ap_amount'],
                'vendor_ap_amount' => $data['vendor_ap_amount'],
            ];

            $receiptData['serial_no'] = "TF-REC-" . sprintf('%03d', $max++);

            $receipt = TransferReceipt::create($receiptData);
            if ($receipt->mode_of_payment == "Cash") {

                $transaction = $this->financialTransactionInterface->makeReceiptTransaction($receipt->id);
                $transferFile->payment_date = $receipt->created_date;
                $transferFile->paid_status = true;
                $transferFile->save();
            }

            if ($receipt->mode_of_payment == "Cheque") {
                $transaction = $this->financialTransactionInterface->makeReceiptChequeTransaction($receipt->id);
            }

            if ($receipt->mode_of_payment == "Online") {
                $transaction = $this->financialTransactionInterface->makeReceiptOnlineTransaction($receipt->id);
                $transferFile->payment_date = $receipt->created_date;
                $transferFile->paid_status = true;
                $transferFile->save();
            }

            if ($receipt->mode_of_payment == "Other") {
                $transaction = $this->financialTransactionInterface->makeReceiptOtherTransaction($receipt->id);
                $transferFile->payment_date = $receipt->created_date;
                $transferFile->paid_status = true;
                $transferFile->save();
            }

            if (isset($data['attachment'])) {
                $receipt->addMedia($data['attachment'])->toMediaCollection('file_transfer_receipt_attachments');
                changeImageDirectoryPermission();
            }
        });
    }

    public function destroy($site_id, $id)
    {
        $this->model()->whereIn('id', $id)->delete();

        return true;
    }

    public function makeActive($site_id, $id)
    {

        $data = [
            'status' => 1,
        ];

        for ($i = 0; $i < count($id); $i++) {
            if ($this->model()->find($id[$i])->status == 0) {
                $transaction = $this->financialTransactionInterface->makeReceiptActiveTransaction($id[$i]);
            }
            $this->model()->where([
                'site_id' => $site_id,
                'id' => $id[$i],
            ])->update($data);
        }
        return true;
    }
}
