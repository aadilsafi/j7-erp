<?php

namespace App\Services\Receipts;

use App\Models\AccountHead;
use App\Models\AccountLedger;
use App\Models\Bank;
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
use App\Services\Receipts\Interface\ReceiptInterface;
use App\Services\FinancialTransactions\FinancialTransactionInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\{URL, Auth, DB, Notification};
use Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf;

class ReceiptService implements ReceiptInterface
{

    private $financialTransactionInterface;

    public function __construct(
        FinancialTransactionInterface $financialTransactionInterface
    ) {
        $this->financialTransactionInterface = $financialTransactionInterface;
    }

    public function model()
    {
        return new Receipt();
    }

    // Store
    public function store($site_id, $requested_data)
    {
        DB::transaction(function () use ($site_id, $requested_data) {
            $data = $requested_data['receipts'];
            for ($i = 0; $i < count($data); $i++) {
                $amount_in_numbers = str_replace(',', '', $data[$i]['amount_in_numbers']);
                $discounted_amount = str_replace(',', '', $requested_data['discounted_amount']);
                $discounted_amount = (float)$discounted_amount;

                if (isset($discounted_amount)) {
                    $amount_in_numbers = (float)$discounted_amount + (float)$amount_in_numbers;
                    $amount_in_numbers = (string)$amount_in_numbers;
                }

                $unit = Unit::find($data[$i]['unit_id']);
                $sales_plan = $unit->salesPlan->toArray();
                if (!isset($data[$i]['bank_name'])) {
                    $data[$i]['bank_id'] =  null;
                    $data[$i]['bank_name'] = null;
                }

                if ($data[$i]['bank_id'] == 0) {

                    if ($data[$i]['mode_of_payment'] == 'Cheque' || $data[$i]['mode_of_payment'] == 'Online') {

                        $bank_last_account_head = Bank::get();
                        $bank_last_account_head_code = collect($bank_last_account_head)->last()->account_head_code;
                        $bank_starting_code = '10209010001010';

                        if ((float)$bank_last_account_head_code >= (float)$bank_starting_code) {
                            $account_head_code = (float)$bank_last_account_head_code + 1;
                        } else {
                            $account_head_code =  (float)$bank_starting_code + 1;
                        }

                        // dd($account_head_code);
                        $bankData = [
                            'site_id' => decryptParams($site_id),
                            'name' => $data[$i]['bank_name'],
                            'slug' => Str::slug($data[$i]['bank_name']),
                            'account_number' => $data[$i]['bank_account_number'],
                            'branch' => $data[$i]['bank_branch'],
                            'branch_code' => $data[$i]['bank_branch_code'],
                            'address' => $data[$i]['bank_address'],
                            'contact_number' => $data[$i]['bank_contact_number'],
                            'status' => true,
                            'comments' => $data[$i]['bank_comments'],
                            'account_head_code' => (string)$account_head_code,
                        ];
                        $bank = Bank::create($bankData);
                        $data[$i]['bank_id'] = $bank->id;
                        $data[$i]['bank_name'] = $bank->name;
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

                $stakeholder = Stakeholder::find($sales_plan[0]['stakeholder']['id']);

                $max = Receipt::max('id') + 1;

                $receiptData = [
                    'site_id' => decryptParams($site_id),
                    'unit_id'  => $data[$i]['unit_id'],
                    'sales_plan_id'  => $sales_plan[0]['id'],
                    'name'  => $stakeholder->full_name,
                    'cnic'  => $stakeholder->cnic,
                    'phone_no' => $stakeholder->contact,
                    'mode_of_payment' => $data[$i]['mode_of_payment'],
                    'other_value' => $data[$i]['other_value'],
                    'cheque_no' => $data[$i]['cheque_no'],
                    'online_instrument_no' => $data[$i]['online_instrument_no'],
                    'transaction_date' => $data[$i]['transaction_date'],
                    'amount_in_words' => numberToWords(str_replace(',', '', $data[$i]['amount_in_numbers'])),
                    'amount_in_numbers' => $amount_in_numbers,
                    'purpose' => 'installments',
                    'installment_number' => '1',
                    'amount_received' => str_replace(',', '', $requested_data['amount_received']),
                    'comments' => $data[$i]['comments'],
                    'status' => ($data[$i]['mode_of_payment'] != 'Cheque') ? 1 : 0,
                    'bank_details' => $data[$i]['bank_name'],
                    'bank_id' => $data[$i]['bank_id'],
                    'created_date' => $requested_data['created_date'] . date(' H:i:s'),
                    'customer_ap_amount' => $data[$i]['customer_ap_amount'],
                    'dealer_ap_amount' => $data[$i]['dealer_ap_amount'],
                    'vendor_ap_amount' => $data[$i]['vendor_ap_amount'],
                ];

                if (isset($discounted_amount) && $discounted_amount > 0) {
                    $receiptData['discounted_amount'] = $discounted_amount;
                }

                if (str_replace(',', '', $requested_data['amount_received']) > str_replace(',', '', $data[$i]['amount_in_numbers'])) {
                    $receipt = ReceiptDraftModel::create($receiptData);

                    if (isset($requested_data['attachment'])) {
                        for($i=0; $i<count($requested_data['attachment']); $i++){
                            $receipt->addMedia($requested_data['attachment'][$i])->toMediaCollection('receipt_attachments');
                            changeImageDirectoryPermission();
                        }
                    }
                    dd($data[$i]);
                    if ($data[$i]['mode_of_payment'] != 'Other') {
                        $remaining_amount = str_replace(',', '', $requested_data['amount_received']) - str_replace(',', '', $data[$i]['amount_in_numbers']);
                    } else {
                        $remaining_amount = str_replace(',', '', $requested_data['amount_received']);
                    }

                    $data = [
                        'unit_name'  => $unit->name,
                        'remaining_amount' =>   $remaining_amount,
                    ];

                    return $data;
                } else {
                    $draft_receipt_data = ReceiptDraftModel::all();

                    if (isset($draft_receipt_data)) {
                        foreach ($draft_receipt_data as $draftReceiptData) {
                            $receiptDraftData = [
                                'site_id' => $draftReceiptData->site_id,
                                'unit_id'  => $draftReceiptData->unit_id,
                                'sales_plan_id'  => $draftReceiptData->sales_plan_id,
                                'name'  => $draftReceiptData->name,
                                'cnic'  => $draftReceiptData->cnic,
                                'phone_no' => $draftReceiptData->phone_no,
                                'mode_of_payment' => $draftReceiptData->mode_of_payment,
                                'other_value' => $draftReceiptData->other_value,
                                'cheque_no' => $draftReceiptData->cheque_no,
                                'online_instrument_no' => $draftReceiptData->online_instrument_no,
                                'transaction_date' => $draftReceiptData->transaction_date,
                                'amount_in_words' => numberToWords($draftReceiptData->amount_in_numbers),
                                'amount_in_numbers' => $draftReceiptData->amount_in_numbers,
                                'comments' => $draftReceiptData->comments,
                                'purpose' => 'installments',
                                'installment_number' => '1',
                                'amount_received' => $draftReceiptData->amount_received,
                                'status' => ($data[$i]['mode_of_payment'] != 'Cheque') ? 1 : 0,
                                'bank_details' => $draftReceiptData->bank_details,
                                'bank_id' => $draftReceiptData->bank_id,
                                'created_date' => $draftReceiptData->created_date,
                                'discounted_amount' => $draftReceiptData->discounted_amount,
                                'serial_no' => sprintf('%03d', $max++),
                                'customer_ap_amount' => $draftReceiptData->customer_ap_amount,
                                'dealer_ap_amount' => $draftReceiptData->dealer_ap_amount,
                                'vendor_ap_amount' => $draftReceiptData->vendor_ap_amount,
                            ];
                            //create receipt from drafts
                            $receipt_Draft = Receipt::create($receiptDraftData);
                            if ($receipt_Draft->mode_of_payment == "Cash") {
                                $transaction = $this->financialTransactionInterface->makeReceiptTransaction($receipt_Draft->id);
                            }

                            if ($receipt_Draft->mode_of_payment == "Cheque") {
                                $transaction = $this->financialTransactionInterface->makeReceiptChequeTransaction($receipt_Draft->id);
                            }

                            if ($receipt_Draft->mode_of_payment == "Online") {
                                $transaction = $this->financialTransactionInterface->makeReceiptOnlineTransaction($receipt_Draft->id);
                            }

                            if ($receipt_Draft->mode_of_payment == "Other") {
                                $transaction = $this->financialTransactionInterface->makeReceiptOtherTransaction($receipt_Draft->id);
                            }

                            if (is_a($transaction, 'Exception') || is_a($transaction, 'GeneralException')) {
                                Log::info(json_encode($transaction));
                                // return apiErrorResponse('invalid_transaction');
                            }

                            $update_installments =  $this->updateInstallments($receipt_Draft);
                        }
                        ReceiptDraftModel::truncate();
                    }

                    $receiptData['serial_no'] = sprintf('%03d', $max++);
                    //here is single without draft
                    $receipt = Receipt::create($receiptData);
                    if ($receipt->mode_of_payment == "Cash") {
                        $transaction = $this->financialTransactionInterface->makeReceiptTransaction($receipt->id);
                    }

                    if ($receipt->mode_of_payment == "Cheque") {
                        $transaction = $this->financialTransactionInterface->makeReceiptChequeTransaction($receipt->id);
                    }

                    if ($receipt->mode_of_payment == "Online") {
                        $transaction = $this->financialTransactionInterface->makeReceiptOnlineTransaction($receipt->id);
                    }

                    if ($receipt->mode_of_payment == "Other") {
                        $transaction = $this->financialTransactionInterface->makeReceiptOtherTransaction($receipt->id);
                    }

                    if (isset($requested_data['attachment'])) {
                        for($i=0; $i<count($requested_data['attachment']); $i++){
                            $receipt->addMedia($requested_data['attachment'][$i])->toMediaCollection('receipt_attachments');
                            changeImageDirectoryPermission();
                        }
                    }
                    // dd($transaction);
                    $update_installments =  $this->updateInstallments($receipt);
                }
            }
        });
    }

    //update Installments
    public function updateInstallments($receipt)
    {
        $sales_plan = SalesPlan::where('unit_id', $receipt->unit_id)->where('status', 1)->with('installments', 'unPaidInstallments')->first();
        $unit = Unit::find($receipt->unit_id);
        $stakeholder = Stakeholder::find($sales_plan->stakeholder_id);

        $installmentFullyPaidUnderAmount = [];
        $installmentPartialyPaidUnderAmount = [];
        $calculate_amount = 0;
        $to_be_paid_calculate_amount = 0;
        $total_calculated_installments = [];
        $amount_to_be_paid = $receipt->amount_in_numbers;

        foreach ($sales_plan->unPaidInstallments as $installment) {
            if ($installment->remaining_amount == 0) {
                $paid_amount = $installment->amount;
                $total_amount = $installment->amount;
            } else {
                $paid_amount = $installment->remaining_amount;
                $total_amount = $installment->amount - $paid_amount;
            }
            $calculate_amount = $calculate_amount + $paid_amount;
            if ($amount_to_be_paid >= $calculate_amount) {
                $partially_paid = 0;
                if ($installment->status == 'partially_paid') {
                    $partially_paid = $installment->paid_amount;
                    $paid_amount = $paid_amount + $installment->paid_amount;
                    $remaining_amount = $installment->amount - $paid_amount;
                }

                $installmentFullyPaidUnderAmount[] = [
                    'id' => $installment->id,
                    'date' => $installment->date,
                    'amount' => $installment->amount,
                    'paid_amount' => $paid_amount,
                    'remaining_amount' => 0,
                    'installment_order' => $installment->installment_order,
                    'partially_paid' => $partially_paid,
                ];
            } else {
                foreach ($installmentFullyPaidUnderAmount as $to_be_paid_installments) {
                    if ($to_be_paid_installments['partially_paid'] !== 0) {
                        $to_be_paid_calculate_amount = $to_be_paid_installments['paid_amount'] - $to_be_paid_installments['partially_paid'];
                    } else {
                        $to_be_paid_calculate_amount = $to_be_paid_calculate_amount + $to_be_paid_installments['paid_amount'];
                    }
                }
                if ($to_be_paid_calculate_amount < $amount_to_be_paid) {

                    if ($to_be_paid_calculate_amount == 0) {
                        $amount_to_be_paid = $installment->amount - $amount_to_be_paid;
                        $paid_amount = $installment->amount - $amount_to_be_paid;
                        $remaining_amount = $installment->amount - $paid_amount;
                    } else {
                        $paid_amount = $amount_to_be_paid - $to_be_paid_calculate_amount;
                        $remaining_amount = $installment->amount - $paid_amount;
                    }
                    if ($installment->status == 'partially_paid') {
                        $partially_paid = $paid_amount;
                        $paid_amount = $paid_amount + $installment->paid_amount;
                        $remaining_amount = $installment->amount - $paid_amount;
                    }

                    $installmentPartialyPaidUnderAmount[] = [
                        'id' => $installment->id,
                        'date' => $installment->date,
                        'amount' => $installment->amount,
                        'paid_amount' => $paid_amount,
                        'remaining_amount' => $remaining_amount,
                        'installment_order' => $installment->installment_order,
                        'partially_paid' => $installment->paid_amount,
                    ];
                }

                break;
            }
        }
        $total_calculated_installments = array_merge($installmentFullyPaidUnderAmount, $installmentPartialyPaidUnderAmount);
        $instalment_numbers = [];
        $total_paid_amount = 0;

        for ($i = 0; $i < count($total_calculated_installments); $i++) {
            $installment = SalesPlanInstallments::find($total_calculated_installments[$i]['id']);
            $installment->paid_amount = $total_calculated_installments[$i]['paid_amount'];
            $installment->remaining_amount = $total_calculated_installments[$i]['remaining_amount'];
            if ($total_calculated_installments[$i]['remaining_amount'] == 0) {
                $installment->status = 'paid';
            } else {
                $installment->status = 'partially_paid';
            }
            $instalment_numbers[] =  $installment->details;
            $purpose = $installment->details;
            $total_paid_amount =   $total_paid_amount + $total_calculated_installments[$i]['paid_amount'];
            $installment->last_paid_at = now();
            $installment->update();
        }
        $update_installment_details = Receipt::find($receipt->id);
        $update_installment_details->purpose = $purpose;
        $update_installment_details->installment_number = json_encode($instalment_numbers);
        $update_installment_details->update();

        $totalAmountOfSalesPlan =  $sales_plan->total_price;
        $down_payment_total = $sales_plan->down_payment_total;
        $approved_sales_plan_date = $sales_plan->approved_date;
        $site_token_percentage = SiteConfigration::where('site_id', $receipt->site_id)->first()->site_token_percentage;
        $token_price = ($site_token_percentage / 100) * $totalAmountOfSalesPlan;
        $installment_date = SalesPlanInstallments::where('sales_plan_id', $sales_plan->id)->where('status', 'paid')->orWhere('status', 'partially_paid')->latest("id")->first()->date;

        $total_committed_amount = SalesPlanInstallments::where('sales_plan_id', $sales_plan->id)->whereDate('date', '<=', $approved_sales_plan_date)->get();
        $total_committed_amount = collect($total_committed_amount)->sum('amount');

        $total_paid_amount = SalesPlanInstallments::where('sales_plan_id', $sales_plan->id)->get();
        $total_paid_amount = collect($total_paid_amount)->sum('paid_amount');

        if ($total_paid_amount <= $token_price) {
            $unit->status_id = 2;
        }

        if ($total_paid_amount > $token_price &&  $total_paid_amount < $total_committed_amount) {
            $unit->status_id = 3;
        }

        if ($total_paid_amount >= $total_committed_amount) {
            if ($unit->status_id <= 3) {
                $unit->is_for_rebate = true;
            }

            $unit->status_id = 5;

            $unitStakeholderData = [
                'site_id' => $receipt->site_id,
                'unit_id' => $unit->id,
                'stakeholder_id' => $stakeholder->id,
            ];
            $stakeholderType = StakeholderType::where(['stakeholder_id' => $stakeholder->id, 'type' => 'C'])->first()->update(['status' => true]);
            $unitStakeholder = UnitStakeholder::create($unitStakeholderData);

            //Here code to disapprove all pending sales plan of same unit
            $pendingSalesPlan =  (new SalesPlan())->where('unit_id', $unit->id)->where('id', '!=', $sales_plan->id)->where('status', 0)->first();
            if ($pendingSalesPlan != null) {
                $pendingSalesPlan->status = 2;
                $pendingSalesPlan->update();
            }
        }

        $unit->update();
        $this->updatePaymentPlanPdf($receipt->salesPlan);
    }

    // update paymenmt plan pdf
    public function updatePaymentPlanPdf($salesPlan)
    {
        $path = public_path('app-assets/pdf/sales-plans/payment-plan');
        $fileName =  'Payment-Plan-' . $salesPlan->unit->id . $salesPlan->unit->floor_unit_number . '-' . $salesPlan->id . '-' .  $salesPlan->stakeholder->id . '.' . 'pdf';

        $role = $salesPlan->user->roles->pluck('name');

        $data = [
            'unit_no' => $salesPlan->unit->floor_unit_number,
            'floor_short_label' => $salesPlan->unit->floor->short_label,
            'category' => $salesPlan->unit->type->name,
            'size' => $salesPlan->unit->gross_area,
            'client_name' => $salesPlan->stakeholder->full_name,
            'client_number' => $salesPlan->stakeholder->mobile_contact,
            'rate' => $salesPlan->unit_price,
            'down_payment_percentage' => $salesPlan->down_payment_percentage,
            'down_payment_total' =>  $salesPlan->down_payment_total,
            'discount_percentage' => $salesPlan->discount_percentage,
            'discount_total' =>  $salesPlan->discount_total,
            'total' => $salesPlan->total_price,
            'sales_person_name' => $salesPlan->user->name,
            'sales_person_contact' => $salesPlan->user->contact,
            'sales_person_status' => $role[0],
            'sales_person_phone_no' => $salesPlan->user->phone_no,
            'sales_person_sales_type' => $salesPlan->sales_type,
            'indirect_source' => $salesPlan->indirect_source,
            'instalments' => collect($salesPlan->installments)->sortBy('installment_order'),
            'remaining_installments' => $salesPlan->installments->where('remaining_amount', '>', 0)->count(),
            'additional_costs' => $salesPlan->additionalCosts,
            'validity' =>  $salesPlan->validity,
            'contact' => $salesPlan->stakeholder->mobile_contact,
            'amount' => $salesPlan->total_price,
            'remaing_amount' => $salesPlan->installments->sum('remaining_amount'),
            'paid_amount' => $salesPlan->installments->sum('paid_amount'),

        ];
        $image = base64_encode(file_get_contents(public_path('app-assets/images/logo/j7global-logo.png')));
        $pdf = Pdf::setOptions(['dpi' => 150, 'defaultFont' => 'sans-serif', 'isHtml5ParserEnabled' => true, 'isRemoteEnabled' => true, 'chroot' => public_path()])->setPaper('letter', 'portrait')->loadView('app.sites.floors.units.sales-plan.sales-plan-templates.pdf-template-02', compact('data', 'image'));

        $pdf->save($path . '/' . $fileName);
    }

    public function update($site_id, $id, $inputs)
    {
    }

    public function destroy($site_id, $id)
    {
        $this->model()->whereIn('id', $id)->delete();

        return true;
    }

    public function revertPayment($site_id, $id)
    {
        $id = explode(",", $id);
        for ($i = 1; $i < count($id); $i++) {
            if ($this->model()->find($id[$i])->status == 0 || $this->model()->find($id[$i])->status == 1) {

                $receipt = $this->model()->find($id[$i]);
                if ($receipt->mode_of_payment == "Cash") {
                    $transaction = $this->financialTransactionInterface->makeReceiptRevertCashTransaction($receipt->id);
                }
                if ($receipt->mode_of_payment == "Cheque") {
                    $transaction = $this->financialTransactionInterface->makeReceiptRevertChequeTransaction($receipt->id);
                }
                if ($receipt->mode_of_payment == "Online") {
                    $transaction = $this->financialTransactionInterface->makeReceiptRevertOnlineTransaction($receipt->id);
                }
                $receipt->status = 3;
                $receipt->update();

                $instalmentNumbers = json_decode($receipt->installment_number);
                $sales_plan = SalesPlan::find($receipt->sales_plan_id);
                $amount_received = (float)$receipt->amount_in_numbers;
                $countData = count($instalmentNumbers);

                for ($j = 0; $j < $countData; $j++) {
                    $installment = SalesPlanInstallments::where('sales_plan_id', $sales_plan->id)->where('details', $instalmentNumbers[$j])->first();

                    if ($amount_received > $installment->amount) {

                        $specficAmount = $installment->amount;
                        $amount_received = $amount_received - $specficAmount;

                        $installment->remaining_amount = $installment->amount;
                        $installment->paid_amount = $installment->paid_amount - $installment->paid_amount;
                    } else {
                        $specficAmount = $amount_received;
                        $amount_received = $amount_received - $specficAmount;

                        $installment->paid_amount = $installment->paid_amount - $specficAmount;
                        $installment->remaining_amount = $installment->remaining_amount + $specficAmount;
                    }

                    if ($installment->remaining_amount == $installment->amount) {
                        $installment->status = 'unpaid';
                    } elseif ($installment->remaining_amount > $installment->amount) {
                        $installment->status = 'paid';
                    } else {
                        $installment->status =  'partially_paid';
                    }
                    $installment->update();
                }

                $totalAmountOfSalesPlan =  $sales_plan->total_price;
                $down_payment_total = $sales_plan->down_payment_total;
                $approved_sales_plan_date = $sales_plan->approved_date;
                $site_token_percentage = SiteConfigration::where('site_id', $receipt->site_id)->first()->site_token_percentage;
                $token_price = ($site_token_percentage / 100) * $totalAmountOfSalesPlan;


                $total_paid_amount = SalesPlanInstallments::where('sales_plan_id', $sales_plan->id)->get();
                $total_paid_amount = collect($total_paid_amount)->sum('paid_amount');

                $total_committed_amount = SalesPlanInstallments::where('sales_plan_id', $sales_plan->id)->whereDate('date', '<=', $approved_sales_plan_date)->get();
                $total_committed_amount = collect($total_committed_amount)->sum('amount');

                $unit = Unit::find($receipt->unit_id);

                if ($total_paid_amount == null || $total_paid_amount == 0) {
                    $unit->status_id = 1;
                    $unit->is_for_rebate = false;
                }

                if ($total_paid_amount <= $token_price && $total_paid_amount > 0) {
                    $unit->status_id = 2;
                    $unit->is_for_rebate = false;
                }

                if ($total_paid_amount > $token_price &&  $total_paid_amount < $total_committed_amount) {
                    $unit->status_id = 3;
                    $unit->is_for_rebate = false;
                }

                if ($total_paid_amount >= $total_committed_amount) {
                    $unit->status_id = 5;
                    $unit->is_for_rebate = true;
                }

                $unit->update();
            }
        }

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

    public function ImportReceipts($site_id)
    {
        // dd(Receipt::first());
        $model = new TempReceipt();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();

        $url = [];

        foreach ($tempdata as $key => $items) {
            foreach ($tempCols as $k => $field) {
                $data[$key][$field] = $items[$tempCols[$k]];
            }

            $stakeholder = Stakeholder::where('cnic', $data[$key]['stakeholder_cnic'])->first();
            $unitId = Unit::select('id')->where('floor_unit_number', $data[$key]['unit_short_label'])->first();

            $salePlan = SalesPlan::where('stakeholder_id', $stakeholder->id)
                ->where('unit_id', $unitId->id)
                ->where('total_price', $data[$key]['total_price'])
                ->where('down_payment_total', $data[$key]['down_payment_total'])
                ->where('validity', $data[$key]['validity'])
                ->first();

            $data[$key]['site_id'] = decryptParams($site_id);
            $data[$key]['sales_plan_id'] = $salePlan->id;
            $data[$key]['unit_id'] = $unitId->id;
            $data[$key]['name'] = $stakeholder->full_name;
            $data[$key]['cnic'] = $stakeholder->cnic;
            $data[$key]['phone_no'] = $stakeholder->contact;
            $data[$key]['amount_in_numbers'] = $data[$key]['amount'];
            $data[$key]['amount_in_words'] = numberToWords($data[$key]['amount']);
            $data[$key]['amount_received'] = $data[$key]['amount'];
            $data[$key]['other_value'] =  $data[$key]['other_payment_mode_value'];
            $data[$key]['online_instrument_no'] = $data[$key]['online_transaction_no'];

            $data[$key]['purpose'] = str_replace('-', ' ', $data[$key]['installment_no']);
            $data[$key]['installment_no'] = json_encode([$data[$key]['purpose']]);
            $data[$key]['is_imported'] = true;

            if ($data[$key]['mode_of_payment'] == 'cheque' || $data[$key]['mode_of_payment'] == 'online') {
                $bank = Bank::where('account_number', $data[$key]['bank_acount_number'])->first();
                if ($bank) {
                    $data[$key]['bank_id'] = $bank->id;
                }
                $data[$key]['bank_details'] = Str::title(str_replace('-', ' ', $data[$key]['bank_name']));
            }

            $data[$key]['installment_number'] = $data[$key]['installment_no'];
            $data[$key]['mode_of_payment'] = Str::title($data[$key]['mode_of_payment']);
            $data[$key]['status'] = $data[$key]['status'] == 'active' ? 1 : 0;
            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();

            $url = $data[$key]['image_url'];

            unset($data[$key]['unit_short_label']);
            unset($data[$key]['stakeholder_cnic']);
            unset($data[$key]['total_price']);
            unset($data[$key]['down_payment_total']);
            unset($data[$key]['validity']);
            unset($data[$key]['other_payment_mode_value']);
            unset($data[$key]['online_transaction_no']);
            unset($data[$key]['installment_no']);
            unset($data[$key]['amount']);
            unset($data[$key]['bank_name']);
            unset($data[$key]['image_url']);

            $receipt = Receipt::create($data[$key]);

            if ($receipt->mode_of_payment == "Cash") {
                $transaction = $this->financialTransactionInterface->makeReceiptTransaction($receipt->id);
            }

            if ($receipt->mode_of_payment == "Cheque") {
                if ($receipt->status == 0) {
                    $transaction = $this->financialTransactionInterface->makeReceiptChequeTransaction($receipt->id);
                } else {
                    $transaction = $this->financialTransactionInterface->makeReceiptActiveTransaction($receipt->id);
                }
            }

            if ($receipt->mode_of_payment == "Online") {
                $transaction = $this->financialTransactionInterface->makeReceiptOnlineTransaction($receipt->id);
            }
            if ($receipt->mode_of_payment == "Cheque") {
                $receipt->addMedia(public_path('app-assets/images/Import/' . $url))->toMediaCollection('receipt_attachments');
                changeImageDirectoryPermission();
            }
            $update_installments =  $this->updateInstallments($receipt);
        }

        return $receipt;
    }
}
