<?php

namespace App\Http\Controllers;

use App\DataTables\TransferReceiptsDatatable;
use App\Models\AccountLedger;
use App\Models\Bank;
use App\Models\FileTitleTransfer;
use App\Models\ReceiptTemplate;
use App\Models\StakeholderType;
use App\Models\Unit;
use Illuminate\Http\Request;

class TransferReceiptController extends Controller
{
    public function index(TransferReceiptsDatatable $dataTable, $site_id)
    {
        //
        $data = [
            'site_id' => $site_id,
            'receipt_templates' => ReceiptTemplate::all(),
        ];
        return $dataTable->with($data)->render('app.sites.file-transfer-receipts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        if (!request()->ajax()) {

            $units = [];
            $fileOwner = [];
            $transferOwner = [];

            $transferFiles = FileTitleTransfer::where('status', 1)->where('paid_status', 0)->get();

            foreach ($transferFiles as $key => $tf) {

                $units[$key] = json_decode($tf->unit_data);
                $units[$key]->full_name = $tf->transferStakeholder->full_name;
                $units[$key]->cnic = $tf->transferStakeholder->cnic;
                $units[$key]->transferFileId = $tf->id;
                $fileOwner[$key] = $tf->stakeholder_data;
                $transferOwner[$key] = $tf->transfer_person_data;
            }

            $data = [
                'site_id' => decryptParams($site_id),
                'units' => $units,
                'banks' => Bank::all(),
            ];

            return view('app.sites.file-transfer-receipts.create', $data);
        } else {
            abort(403);
        }
    }

    public function getTransferFileData(Request $request)
    {
        $transferFiles = FileTitleTransfer::find($request->getTransferFileData);

        $fileOwner = json_decode($transferFiles->stakeholder_data);
        $fileOwner->country = $transferFiles->stakeholder->country->name;
        $fileOwner->state = $transferFiles->stakeholder->stae->name ?? '';
        $fileOwner->city = $transferFiles->stakeholder->city->name ?? '';

        $transferOwner = json_decode($transferFiles->transfer_person_data);
        $transferOwner->country = $transferFiles->transferStakeholder->country->name;
        $transferOwner->state = $transferFiles->transferStakeholder->state->name;
        $transferOwner->city = $transferFiles->transferStakeholder->city->name;

        $customerApAccount  = StakeholderType::where(['stakeholder_id' => $transferFiles->transfer_person_id, 'type' => 'C'])->first();
        $dealerApAccount  = StakeholderType::where(['stakeholder_id' => $transferFiles->transfer_person_id, 'type' => 'D'])->first();
        $vendorApAccount  = StakeholderType::where(['stakeholder_id' => $transferFiles->transfer_person_id, 'type' => 'V'])->first();

        // Customer Ap Amount
        if (isset($customerApAccount)) {
            $customerLedger = AccountLedger::where('account_head_code', $customerApAccount->payable_account)->get();
            $customerDebit = collect($customerLedger)->sum('debit');
            $customerCredit = collect($customerLedger)->sum('credit');
            $customerPayableAmount = (float)$customerCredit - (float)$customerDebit;
        } else {
            $customerPayableAmount = 0;
        }


        // Vendor Ap Amount
        if (isset($dealerApAccount)) {
            $dealerLedger = AccountLedger::where('account_head_code', $dealerApAccount->payable_account)->get();
            $dealerDebit = collect($dealerLedger)->sum('debit');
            $dealerCredit = collect($dealerLedger)->sum('credit');
            $dealerPayableAmount = (float)$dealerCredit - (float)$dealerDebit;
        } else {
            $dealerPayableAmount = 0;
        }

        // Vendor Ap Amount
        if (isset($vendorApAccount)) {
            $vendorLedger = AccountLedger::where('account_head_code', $vendorApAccount->payable_account)->get();
            $vendorDebit = collect($vendorLedger)->sum('debit');
            $vendorCredit = collect($vendorLedger)->sum('credit');
            $vendorPayableAmount = (float)$vendorCredit - (float)$vendorDebit;
        } else {
            $vendorPayableAmount = 0;
        }

        $total_payable_amount  = $customerPayableAmount  +  $dealerPayableAmount + $vendorPayableAmount;

        return response()->json([
            'success' => true,
            'unit_type' => $transferFiles->unit->type->name,
            'unit_floor' => $transferFiles->unit->floor->name,
            'unit_name' => $transferFiles->unit->name,
            'customerApAccount' => $customerApAccount,
            'dealerApAccount' => $dealerApAccount,
            'vendorApAccount' => $vendorApAccount,
            'customerPayableAmount' => $customerPayableAmount,
            'dealerPayableAmount' => $dealerPayableAmount,
            'vendorPayableAmount' => $vendorPayableAmount,
            'total_payable_amount' => $total_payable_amount,
            'fileOwner' => $fileOwner,
            'transferOwner' => $transferOwner,
        ], 200);
    }
}
