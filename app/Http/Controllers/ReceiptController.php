<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Unit;
use App\Models\SalesPlan;
use Illuminate\Http\Request;
use App\DataTables\ReceiptsDatatable;
use App\Http\Requests\Receipts\store;
use App\Models\Receipt;
use App\Models\ReceiptTemplate;
use App\Models\SalesPlanInstallments;
use App\Models\Stakeholder;
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
            'receipt_templates' => ReceiptTemplate::all(),
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
                $data = $request->all();
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
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($site_id, $id)
    {
        //
        $site_id = decryptParams($site_id);
        $receipt = Receipt::find(decryptParams($id));
        $image = $receipt->getFirstMediaUrl('receipt_attachments');
        // dd();
        $receipt_installment_numbers = str_replace(str_split('[]"'), '', $receipt->installment_number);
        $installmentNumbersArray = explode(",",$receipt_installment_numbers);
        $last_index = array_key_last ( $installmentNumbersArray );
        $first_letter = str_split( $installmentNumbersArray[$last_index]);
        $unit_data =  $receipt->unit;
        $last_paid_installment_id = SalesPlanInstallments::where('details','LIKE', '%'.$first_letter[0].'%')->where('sales_plan_id',$receipt->sales_plan_id)->first()->id;
        $unpadid_installments = SalesPlanInstallments::where('id','>',$last_paid_installment_id)->where('sales_plan_id',$receipt->sales_plan_id)->orderBy('installment_order', 'asc')->get();
        $paid_installments = SalesPlanInstallments::where('id','<=',$last_paid_installment_id)->where('sales_plan_id',$receipt->sales_plan_id)->orderBy('installment_order', 'asc')->get();
        $stakeholder_data = Stakeholder::where('cnic',$receipt->cnic)->first();
        return view('app.sites.receipts.preview',compact('site_id','unit_data','stakeholder_data','paid_installments','unpadid_installments','receipt','image'));
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
        abort(403);
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
        abort(403);
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
        $sales_plan = SalesPlan::where('unit_id',$request->unit_id)->where('status',1)->with('PaidorPartiallyPaidInstallments','unPaidInstallments')->first();
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
                    'amount' => number_format($installment->amount),
                    'paid_amount' => number_format($paid_amount),
                    'remaining_amount' => 0.0,
                    'installment_order' => $installment->installment_order,
                    'partially_paid' => number_format($partially_paid),
                    'detail' => $installment->details,
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
                        'amount' => number_format($installment->amount),
                        'paid_amount' => number_format($paid_amount),
                        'remaining_amount' => number_format($remaining_amount),
                        'installment_order' => $installment->installment_order,
                        'partially_paid' => number_format($installment->paid_amount),
                        'detail' => $installment->details,
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
            'amount_to_be_paid' => number_format($request->amount),
            'already_paid' =>$sales_plan->PaidorPartiallyPaidInstallments,
        ], 200);
    }

    public function printReceipt($site_id,$receipt_id,$template_id){

        $receipt_data = Receipt::find($receipt_id);

        $template =ReceiptTemplate::find(decryptParams($template_id));

        $unit = Unit::find($receipt_data->unit_id);

        $preview_data = [
            'unit_name' => $unit->name,
            'unit_type' => $unit->type->name,
            'unit_floor' => $unit->floor->name,
            'name' => $receipt_data->name,
            'cnic' => str_split( $receipt_data->cnic),
            'mode_of_payment' => $receipt_data->mode_of_payment,
            'other_value' => $receipt_data->other_value,
            'pay_order' => $receipt_data->pay_order,
            'cheque_no' => $receipt_data->cheque_no,
            'online_instrument_no' => $receipt_data->online_instrument_no,
            'drawn_on_bank' => $receipt_data->drawn_on_bank,
            'transaction_date' => $receipt_data->transaction_date,
            'amount_in_numbers' => $receipt_data->amount_in_numbers,
            'amount_in_words' =>  numberToWords($receipt_data->amount_in_numbers),
            'purpose' => $receipt_data->purpose,
            'other_purpose' => $receipt_data->other_purpose,
            'installment_number' =>str_replace(str_split('[]"'), '', $receipt_data->installment_number),
            'online_instrument_no' => $receipt_data->online_instrument_no,
        ];

        return view('app.sites.receipts.templates.'.$template->slug ,compact('preview_data'));
    }

}
