<?php

namespace App\Services\Receipts;

use App\Models\Unit;
use App\Models\Receipt;
use App\Models\SalesPlan;
use App\Models\SalesPlanInstallments;
use App\Models\Stakeholder;
use App\Services\Receipts\Interface\ReceiptInterface;

class ReceiptService implements ReceiptInterface
{

    public function model()
    {
        return new Receipt();
    }

    // Store
    public function store($site_id, $data)
    {
        for ($i = 0; $i < count($data); $i++) {
            $unit = Unit::find($data[$i]['unit_id']);
            $sales_plan = $unit->salesPlan->toArray();
            $sales_plan_id = $sales_plan[0]['id'];
            $stakeholder = Stakeholder::find($sales_plan[0]['stakeholder']['id']);
            $name = $stakeholder->full_name;
            $cnic = $stakeholder->cnic;
            $phone_no = $stakeholder->contact;

            $receiptData = [
                'site_id' => decryptParams($site_id),
                'unit_id'  => $data[$i]['unit_id'],
                'sales_plan_id'  => $sales_plan_id,
                'name'  => $name,
                'cnic'  => $cnic,
                'phone_no' => $phone_no,
                'mode_of_payment' => $data[$i]['mode_of_payment'],
                'other_value' => $data[$i]['other_value'],
                // 'pay_order' => $data[$i]['pay_order'],
                'cheque_no' => $data[$i]['cheque_no'],
                'online_instrument_no' => $data[$i]['online_instrument_no'],
                // 'drawn_on_bank' => $data[$i]['drawn_on_bank'],
                'transaction_date' => $data[$i]['transaction_date'],
                'amount_in_words' => '',
                'amount_in_numbers' => $data[$i]['amount_in_numbers'],
                'purpose' => 'installments',
                'installment_number' => '1',
            ];

            $receipt = Receipt::insert($receiptData);

            $sales_plan = SalesPlan::where('unit_id', $data[$i]['unit_id'])->where('status', 1)->with('installments', 'unPaidInstallments')->first();
            $installmentFullyPaidUnderAmount = [];
            $installmentPartialyPaidUnderAmount = [];
            $calculate_amount = 0.0;
            $to_be_paid_calculate_amount = 0.0;
            $total_calculated_installments = [];
            $amount_to_be_paid = $data[$i]['amount_in_numbers'];

            foreach($sales_plan->unPaidInstallments as $installment){
                if($installment->remaining_amount == 0){
                    $paid_amount = $installment->amount;
                    $total_amount = $installment->amount;
                }
                else{
                    $paid_amount = $installment->remaining_amount;
                    $total_amount = $installment->amount - $paid_amount;
                }
                $calculate_amount = $calculate_amount + $paid_amount;
                if($amount_to_be_paid >= $calculate_amount){
                    $partially_paid = 0.0;
                    if($installment->status == 'partially_paid'){
                        $partially_paid = $installment->paid_amount;
                        $paid_amount = $paid_amount + $installment->paid_amount;
                        $remaining_amount = $installment->amount - $paid_amount;
                    }

                    $installmentFullyPaidUnderAmount[] = [
                        'id' => $installment->id,
                        'date' => $installment->date,
                        'amount' => $installment->amount,
                        'paid_amount' => $paid_amount,
                        'remaining_amount' => 0.0,
                        'installment_order' => $installment->installment_order,
                        'partially_paid' => $partially_paid,
                    ];
                }
                else{
                    foreach($installmentFullyPaidUnderAmount as $to_be_paid_installments){
                        if($to_be_paid_installments['partially_paid'] !== 0.0){
                            $to_be_paid_calculate_amount = $to_be_paid_installments['paid_amount'] - $to_be_paid_installments['partially_paid'];
                        }
                        else{
                            $to_be_paid_calculate_amount = $to_be_paid_calculate_amount + $to_be_paid_installments['paid_amount'];
                        }
                    }
                    if($to_be_paid_calculate_amount < $amount_to_be_paid){

                        if($to_be_paid_calculate_amount == 0){
                            $amount_to_be_paid = $installment->amount - $amount_to_be_paid;
                            $paid_amount =$installment->amount - $amount_to_be_paid;
                            $remaining_amount = $installment->amount - $paid_amount;
                        }
                        else{
                            $paid_amount = $amount_to_be_paid - $to_be_paid_calculate_amount;
                            $remaining_amount = $installment->amount - $paid_amount;
                        }
                        if($installment->status == 'partially_paid'){
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
            // dd($total_calculated_installments);
            for ($i = 0; $i < count($total_calculated_installments); $i++) {
                $installment = SalesPlanInstallments::find($total_calculated_installments[$i]['id']);
                $installment->paid_amount = $total_calculated_installments[$i]['paid_amount'];
                $installment->remaining_amount = $total_calculated_installments[$i]['remaining_amount'];
                if ($total_calculated_installments[$i]['remaining_amount'] == 0.0) {
                    $installment->status = 'paid';
                } else {
                    $installment->status = 'partially_paid';
                }
                $installment->update();
            }
        }
    }

    public function update($site_id, $id, $inputs)
    {
    }

    public function destroy($site_id, $id)
    {
        $this->model()->whereIn('id', $id)->delete();

        return true;

    }
}
