<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Unit;
use App\Models\SalesPlan;
use Illuminate\Http\Request;
use App\DataTables\ReceiptsDatatable;
use App\Http\Requests\Receipts\store;
use App\Services\Receipts\Interface\ReceiptInterface;

class ReceiptController extends Controller
{

    private $receiptInterface;

    public function __construct(
        ReceiptInterface $receiptInterface
    ) {
        $this->receiptInterface = $receiptInterface;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ReceiptsDatatable $dataTable, $site_id)
    {
        //
        $data = [
            'site_id' => $site_id,
        ];
        return $dataTable->with($data)->render('app.sites.receipts.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        //
        if (!request()->ajax()) {

            $data = [
                'site_id' => decryptParams($site_id),
                'units' => (new Unit())->with('salesPlan','salesPlan.installments')->get(),
            ];
            return view('app.sites.receipts.create',$data);
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
    public function store(store $request,$site_id)
    {
        //
        try {
            if (!request()->ajax()) {
                $data = $request->receipts;
                $record = $this->receiptInterface->store($site_id, $data);;
                return redirect()->route('sites.receipts.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.receipts.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function destroySelected(Request $request, $site_id)
    {
        try {
            $site_id = decryptParams($site_id);
            if (!request()->ajax()) {
                if ($request->has('chkRole')) {

                    $record = $this->receiptInterface->destroy($site_id, $request->chkRole);

                    if ($record) {
                        return redirect()->route('sites.receipts.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_deleted'));
                    } else {
                        return redirect()->route('sites.receipts.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function getUnitTypeAndFloorAjax(Request $request){

        $unit = Unit::find($request->unit_id);

        return response()->json([
            'success' => true,
            'unit_id' => $unit->id,
            'unit_type' => $unit->type->name,
            'unit_floor' => $unit->floor->name,
            'unit_name' => $unit->name,
        ], 200);

    }

    public function getUnpaidInstallments(Request $request)
    {
        $sales_plan = SalesPlan::where('unit_id',$request->unit_id)->where('status',1)->with('installments','unPaidInstallments')->first();
        $installmentFullyPaidUnderAmount = [];
        $installmentPartialyPaidUnderAmount = [];
        $calculate_amount = 0.0;
        $to_be_paid_calculate_amount = 0.0;
        $total_calculated_installments = [];
        $amount_to_be_paid = $request->amount;
        $total_installment_required_amount = 0.0;
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
        foreach($sales_plan->unPaidInstallments as $installment){
            if($installment->status == 'partially_paid'){
                $total_installment_required_amount = $total_installment_required_amount +  $installment->remaining_amount;
            }
            else{
                $total_installment_required_amount = $total_installment_required_amount +  $installment->amount;
            }
        }

        $total_calculated_installments = array_merge($installmentFullyPaidUnderAmount,$installmentPartialyPaidUnderAmount);
        $amount_entered = (float)$request->amount;
        if($amount_entered > $total_installment_required_amount){
            return response()->json([
                'success' => false,
                'message' => 'Entered Amount is greater than Required Amount. Required Amount is '.$total_installment_required_amount,
            ], 200);
        }
        return response()->json([
            'success' => true,
            'sales_plan' =>$sales_plan,
            'unpaid_installments_to_be_paid' => $installmentFullyPaidUnderAmount,
            'unpaid_installments_to_be_partialy_paid' => $installmentPartialyPaidUnderAmount,
            'total_calculated_installments' => $total_calculated_installments,
            'total_installment_required_amount' => $total_installment_required_amount,
            'amount_to_be_paid' => $request->amount,
        ], 200);
    }

}
