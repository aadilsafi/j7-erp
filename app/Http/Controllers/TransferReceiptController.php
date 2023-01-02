<?php

namespace App\Http\Controllers;

use App\DataTables\TransferReceiptsDatatable;
use App\Http\Requests\FileTransferReceipt\store;
use App\Models\AccountLedger;
use App\Models\Bank;
use App\Models\FileTitleTransfer;
use App\Models\PaymentVocuher;
use App\Models\ReceiptTemplate;
use App\Models\SalesPlan;
use App\Models\Site;
use App\Models\StakeholderType;
use App\Models\TransferReceipt;
use App\Services\TransferFileReceipts\TransferFileReceiptInterface;
use Exception;
use Illuminate\Http\Request;

class TransferReceiptController extends Controller
{

    private $transferFileReceiptInterface;

    public function __construct(
        TransferFileReceiptInterface $transferFilereceiptInterface
    ) {
        $this->transferFileReceiptInterface = $transferFilereceiptInterface;
    }

    public function index(TransferReceiptsDatatable $dataTable, $site_id)
    {

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
                $units[$key]->full_name = $tf->stakeholder->full_name;
                $units[$key]->cnic = $tf->stakeholder->cnic;
                $units[$key]->transferFileId = $tf->id;

                $units[$key]->tp_full_name = $tf->transferStakeholder->full_name;
                $units[$key]->tp_cnic = $tf->transferStakeholder->cnic;

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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(store $request, $site_id)
    {
        $validator = \Validator::make($request->all(), [
            'created_date' => 'required',
        ]);

        $validator->validate();
        try {
            if (!request()->ajax()) {
                $data = $request->all();
                $record = $this->transferFileReceiptInterface->store($site_id, $data);

                return redirect()->route('sites.file-transfer-receipts.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.file-transfer-receipts.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($site_id, $id)
    {
        $site = (new Site())->find(decryptParams($site_id));
        $receipt = (new TransferReceipt())->find(decryptParams($id));
        $image = $receipt->getFirstMediaUrl('file_transfer_receipt_attachments');

        $fileOwner = json_decode($receipt->TransferFile->stakeholder_data);
        $fileOwner->residentialCountry = $receipt->TransferFile->stakeholder->residentialCountry ?? [];
        $fileOwner->residentialState = $receipt->TransferFile->stakeholder->residentialState ?? [];
        $fileOwner->residentialCity = $receipt->TransferFile->stakeholder->residentialCity ?? [];

        $fileOwner->mailingCountry = $receipt->TransferFile->stakeholder->mailingCountry ?? [];
        $fileOwner->mailingState = $receipt->TransferFile->stakeholder->mailingState ?? [];
        $fileOwner->mailingCity = $receipt->TransferFile->stakeholder->mailingCity ?? [];

        $fileOwner->nationalityCountry = $receipt->TransferFile->stakeholder->nationalityCountry ?? [];

        $transferOwner = json_decode($receipt->TransferFile->transfer_person_data);
        $transferOwner->country = $receipt->TransferFile->transferStakeholder->country->name ?? '';
        $transferOwner->state = $receipt->TransferFile->transferStakeholder->state->name ?? '';
        $transferOwner->city = $receipt->TransferFile->transferStakeholder->city->name ?? '';

        $transferOwner->residentialCountry = $receipt->TransferFile->transferStakeholder->residentialCountry ?? [];
        $transferOwner->residentialState = $receipt->TransferFile->transferStakeholder->residentialState ?? [];
        $transferOwner->residentialCity = $receipt->TransferFile->transferStakeholder->residentialCity ?? [];

        $transferOwner->mailingCountry = $receipt->TransferFile->transferStakeholder->mailingCountry ?? [];
        $transferOwner->mailingState = $receipt->TransferFile->transferStakeholder->mailingState ?? [];
        $transferOwner->mailingCity = $receipt->TransferFile->transferStakeholder->mailingCity ?? [];

        $transferOwner->nationalityCountry = $receipt->TransferFile->transferStakeholder->nationalityCountry ?? [];
        $sales_plan = SalesPlan::find($receipt->TransferFile->sales_plan_id);

        return view('app.sites.file-transfer-receipts.preview', [
            'site' => $site,
            'receipt' => $receipt,
            'unit_data' => $receipt->unit,
            'image' => $image,
            'fileOwner' => $fileOwner,
            'transferOwner' => $transferOwner,
            'sales_plan' => $sales_plan,
        ]);
    }

    public function makeActiveSelected(Request $request, $site_id)
    {
        try {
            $site_id = decryptParams($site_id);
            if (!request()->ajax()) {
                if ($request->has('chkRole')) {

                    $record = $this->transferFileReceiptInterface->makeActive($site_id, $request->chkRole);

                    if ($record) {
                        return redirect()->route('sites.file-transfer-receipts.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('Status Changed'));
                    } else {
                        return redirect()->route('sites.file-transfer-receipts.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.file-transfer-receipts.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
    public function getTransferFileData(Request $request)
    {
        $transferFiles = FileTitleTransfer::find($request->transfer_file_id);

        $fileOwner = json_decode($transferFiles->stakeholder_data);
        $fileOwner->country = $transferFiles->stakeholder->country->name ?? '';
        $fileOwner->state = $transferFiles->stakeholder->stae->name ?? '';
        $fileOwner->city = $transferFiles->stakeholder->city->name ?? '';

        $transferOwner = json_decode($transferFiles->transfer_person_data);
        $transferOwner->country = $transferFiles->transferStakeholder->country->name ?? '';
        $transferOwner->state = $transferFiles->transferStakeholder->state->name ?? '';
        $transferOwner->city = $transferFiles->transferStakeholder->city->name ?? '';

        $stakeholderType = StakeholderType::where('stakeholder_id', $transferFiles->stakeholder_id)->get();
        $customerApAccount  = StakeholderType::where(['stakeholder_id' => $transferFiles->stakeholder_id, 'type' => 'C'])->first();
        $dealerApAccount  = StakeholderType::where(['stakeholder_id' => $transferFiles->stakeholder_id, 'type' => 'D'])->first();
        $vendorApAccount  = StakeholderType::where(['stakeholder_id' => $transferFiles->stakeholder_id, 'type' => 'V'])->first();

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

        // If payable created but entries not hit
        // For Customer
        $payment_voucher = PaymentVocuher::where('customer_id', $transferFiles->stakeholder_id)->where('status', 0)->get();
        if (count($payment_voucher) > 0) {
            $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            $customerPayableAmount = (float)$customerPayableAmount - (float)$debit_value;
        }

        // For cheque inactive
        $payment_voucher = PaymentVocuher::where('customer_id', $transferFiles->stakeholder_id)->where('status', 1)->where('cheque_status', 0)->get();

        if (count($payment_voucher) > 0) {
            $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            $customerPayableAmount = (float)$customerPayableAmount - (float)$debit_value;
        }
        //End For Customer

        // For Dealer
        $payment_voucher = PaymentVocuher::where('dealer_id', $transferFiles->stakeholder_id)->where('status', 0)->get();
        if (count($payment_voucher) > 0) {
            $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            $dealerPayableAmount = (float)$dealerPayableAmount - (float)$debit_value;
        }

        // For cheque inactive
        $payment_voucher = PaymentVocuher::where('dealer_id', $transferFiles->stakeholder_id)->where('status', 1)->where('cheque_status', 0)->get();

        if (count($payment_voucher) > 0) {
            $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            $dealerPayableAmount = (float)$dealerPayableAmount - (float)$debit_value;
        }
        //End For Dealer

        // For Dealer
        $payment_voucher = PaymentVocuher::where('vendor_id', $transferFiles->stakeholder_id)->where('status', 0)->get();
        if (count($payment_voucher) > 0) {
            $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            $vendorPayableAmount = (float)$vendorPayableAmount - (float)$debit_value;
        }

        // For cheque inactive
        $payment_voucher = PaymentVocuher::where('vendor_id', $transferFiles->stakeholder_id)->where('status', 1)->where('cheque_status', 0)->get();

        if (count($payment_voucher) > 0) {
            $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            $vendorPayableAmount = (float)$vendorPayableAmount - (float)$debit_value;
        }
        //End For Dealer

        // End of payment voucher details

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
            'transferFile' => $transferFiles,
        ], 200);
    }
}
