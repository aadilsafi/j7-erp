<?php

namespace App\Services\Receipts;

use App\Models\Unit;
use App\Models\Receipt;
use App\Models\ReceiptDraftModel;
use App\Models\SalesPlan;
use App\Models\Stakeholder;
use App\Models\UnitStakeholder;
use App\Models\SiteConfigration;
use App\Models\SalesPlanInstallments;
use App\Services\Receipts\Interface\ReceiptInterface;

class ReceiptService implements ReceiptInterface
{

    public function model()
    {
        return new Receipt();
    }

    // Store
    public function store($site_id, $requested_data)
    {
        $data = $requested_data['receipts'];
        $amount_received = $requested_data['amount_received'];

        for ($i = 0; $i < count($data); $i++) {
            $unit = Unit::find($data[$i]['unit_id']);
            $sales_plan = $unit->salesPlan->toArray();
            $sales_plan_id = $sales_plan[0]['id'];
            $stakeholder = Stakeholder::find($sales_plan[0]['stakeholder']['id']);
            $name = $stakeholder->full_name;
            $cnic = $stakeholder->cnic;
            $phone_no = $stakeholder->contact;
            $receipt_status = 0;
            if ($data[$i]['mode_of_payment'] != 'Cheque') {
                $receipt_status = 1;
            }

            $receiptData = [
                'site_id' => decryptParams($site_id),
                'unit_id'  => $data[$i]['unit_id'],
                'sales_plan_id'  => $sales_plan_id,
                'name'  => $name,
                'cnic'  => $cnic,
                'phone_no' => $phone_no,
                'mode_of_payment' => $data[$i]['mode_of_payment'],
                'other_value' => $data[$i]['other_value'],
                'cheque_no' => $data[$i]['cheque_no'],
                'online_instrument_no' => $data[$i]['online_instrument_no'],
                'transaction_date' => $data[$i]['transaction_date'],
                'amount_in_words' => numberToWords($data[$i]['amount_in_numbers']),
                'amount_in_numbers' => $data[$i]['amount_in_numbers'],
                'purpose' => 'installments',
                'installment_number' => '1',
                'amount_received' => $requested_data['amount_received'],
                'comments' => $data[$i]['comments'],
                'status' => $receipt_status,
                'bank_details' => $data[$i]['bank_details']
            ];

            if ($amount_received > $data[$i]['amount_in_numbers']) {
                $receipt = ReceiptDraftModel::create($receiptData);

                if (isset($requested_data['attachment'])) {
                    $receipt->addMedia($requested_data['attachment'])->toMediaCollection('receipt_attachments');
                }

                $remaining_amount = $amount_received - $data[$i]['amount_in_numbers'];

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
                            'amount_received' => $requested_data['amount_received'],
                            'status' => $receipt_status,
                            'bank_details' => $draftReceiptData->bank_details,
                        ];
                        $receipt_Draft = Receipt::create($receiptDraftData);
                        $update_installments =  $this->updateInstallments($receipt_Draft);
                    }
                    ReceiptDraftModel::truncate();
                }

                $receipt = Receipt::create($receiptData);

                if (isset($requested_data['attachment'])) {
                    $receipt->addMedia($requested_data['attachment'])->toMediaCollection('receipt_attachments');
                }

                $update_installments =  $this->updateInstallments($receipt);
            }
        }
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
        $installment_date = SalesPlanInstallments::where('sales_plan_id', $sales_plan->id)->where('status','paid')->orWhere('status','partially_paid')->latest("id")->first()->date;

        if ($total_paid_amount >= $token_price) {
            $unit->status_id = 2;
        }

        if ($total_paid_amount > $token_price &&  $installment_date < $approved_sales_plan_date) {
            $unit->status_id = 3;
        }

        if ($installment_date >= $approved_sales_plan_date) {

            $unit->status_id = 5;

            $unitStakeholderData = [
                'site_id' => $receipt->site_id,
                'unit_id' => $unit->id,
                'stakeholder_id' => $stakeholder->id,
            ];

            $unitStakeholder = UnitStakeholder::create($unitStakeholderData);
        }

        $unit->update();

    }

    public function update($site_id, $id, $inputs)
    {
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
            $this->model()->where([
                'site_id' => $site_id,
                'id' => $id[$i],
            ])->update($data);
        }

        return true;
    }
}