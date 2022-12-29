<?php

namespace App\Http\Controllers;

use App\DataTables\ImportReceiptsDataTable;
use App\DataTables\ImportSalesPlanInstallmentsDataTable;
use Exception;
use App\Models\Unit;
use App\Models\SalesPlan;
use Illuminate\Http\Request;
use App\DataTables\ReceiptsDatatable;
use App\Http\Requests\Receipts\store;
use App\Imports\ReceiptsImport;
use App\Models\AccountLedger;
use App\Models\Bank;
use App\Models\PaymentVocuher;
use App\Models\Receipt;
use App\Models\ReceiptDraftModel;
use App\Models\ReceiptTemplate;
use App\Models\SalesPlanInstallments;
use App\Models\Site;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Models\TempReceipt;
use App\Services\Receipts\Interface\ReceiptInterface;
use App\Services\CustomFields\CustomFieldInterface;
use Redirect;

class ReceiptController extends Controller
{

    private $receiptInterface;

    public function __construct(
        ReceiptInterface $receiptInterface,
        CustomFieldInterface $customFieldInterface
    ) {
        $this->receiptInterface = $receiptInterface;
        $this->customFieldInterface = $customFieldInterface;
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

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->receiptInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site_id' => decryptParams($site_id),
                'units' => (new Unit())->with('salesPlan', 'salesPlan.installments', 'salesPlan.PaidorPartiallyPaidInstallments')->get(),
                'draft_receipts' => ReceiptDraftModel::all(),
                'customFields' => $customFields,
                'banks' => Bank::all(),
            ];
            return view('app.sites.receipts.create', $data);
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
                dd($data);
                $record = $this->receiptInterface->store($site_id, $data);
                // if (is_a($record, 'GeneralException')  || is_a($record, 'Exception')) {
                //     return redirect()->route('sites.receipts.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess($record->getMessage());
                // } else
                if (isset($record['remaining_amount'])) {
                    return redirect()->route('sites.receipts.create', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess('Data against ' . $record['unit_name'] . ' saved as draft. Remaining amount is ' . $record['remaining_amount'])->with('remaining_amount', $record['remaining_amount']);
                } else {
                    return redirect()->route('sites.receipts.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.receipts.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger($ex->getMessage());
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
        $receipt = (new Receipt())->find(decryptParams($id));
        $image = $receipt->getFirstMediaUrl('receipt_attachments');

        $installmentNumbersArray = json_decode($receipt->installment_number);

        $lastInstallment = array_pop($installmentNumbersArray);
        $unit_data =  $receipt->unit;

        $last_paid_installment_id = SalesPlanInstallments::where('sales_plan_id', $receipt->sales_plan_id)
            ->whereRaw('LOWER(details) = (?)', [strtolower($lastInstallment)])->first()->id;

        $unpaid_installments = SalesPlanInstallments::where('id', '>', $last_paid_installment_id)->where('sales_plan_id', $receipt->sales_plan_id)->orderBy('installment_order', 'asc')->get();
        $paid_installments = SalesPlanInstallments::where('id', '<=', $last_paid_installment_id)->where('sales_plan_id', $receipt->sales_plan_id)->orderBy('installment_order', 'asc')->get();
        $stakeholder_data = Stakeholder::where('cnic', $receipt->cnic)->first();
        // if($lastIntsalmentStatus == 'paid'){
        //     $paid_installments = SalesPlanInstallments::all();
        //     $unpadid_installments = null;
        // }
        // dd($stakeholder_data);
        $sales_plan = SalesPlan::find($receipt->sales_plan_id);

        return view('app.sites.receipts.preview', [
            'site' => $site,
            'receipt' => $receipt,
            'image' => $image,
            'unit_data' => $unit_data,
            'paid_installments' => $paid_installments,
            'unpaid_installments' => $unpaid_installments,
            'stakeholder_data' => $stakeholder_data,
            'sales_plan' => $sales_plan,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($site_id, $id)
    {
        abort(403);
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

    public function destroyDraft(Request $request, $site_id)
    {
        ReceiptDraftModel::truncate();

        return redirect()->route('sites.receipts.index', ['site_id' => $site_id])->withSuccess(__('Draft Cleared'));
    }

    public function makeActiveSelected(Request $request, $site_id)
    {
        try {
            $site_id = decryptParams($site_id);
            if (!request()->ajax()) {
                if ($request->has('chkRole')) {

                    $record = $this->receiptInterface->makeActive($site_id, $request->chkRole);

                    if ($record) {
                        return redirect()->route('sites.receipts.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('Status Changed'));
                    } else {
                        return redirect()->route('sites.receipts.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.receipts.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }


    public function getUnitTypeAndFloorAjax(Request $request)
    {

        $unit = Unit::find($request->unit_id);
        $sales_plan = SalesPlan::where('unit_id', $request->unit_id)->where('status', 1)->with('stakeholder')->first();
        $stakeholders = $sales_plan->stakeholder;

        $stakeholderType = StakeholderType::where('stakeholder_id', $stakeholders->id)->get();

        $customerApAccount  = StakeholderType::where(['stakeholder_id' => $stakeholders->id, 'type' => 'C'])->first();

        $dealerApAccount  = StakeholderType::where(['stakeholder_id' => $stakeholders->id, 'type' => 'D'])->first();

        $vendorApAccount  = StakeholderType::where(['stakeholder_id' => $stakeholders->id, 'type' => 'V'])->first();

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
        $payment_voucher = PaymentVocuher::where('customer_id', $stakeholders->id)->where('status', 0)->get();
        if (count($payment_voucher) > 0) {
            $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            $customerPayableAmount = (float)$customerPayableAmount - (float)$debit_value;
        }

        // For cheque inactive
        // $payment_voucher = PaymentVocuher::where('customer_id', $stakeholders->id)->where('status', 1)->where('cheque_status', 0)->get();

        // if (count($payment_voucher) > 0) {
        //     $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
        //     $customerPayableAmount = (float)$customerPayableAmount - (float)$debit_value;
        // }
        //End For Customer

        // For Dealer
        $payment_voucher = PaymentVocuher::where('dealer_id', $stakeholders->id)->where('status', 0)->get();
        if (count($payment_voucher) > 0) {
            $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            $dealerPayableAmount = (float)$dealerPayableAmount - (float)$debit_value;
        }

        // For cheque inactive
        // $payment_voucher = PaymentVocuher::where('dealer_id', $stakeholders->id)->where('status', 1)->where('cheque_status', 0)->get();

        // if (count($payment_voucher) > 0) {
        //     $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
        //     $dealerPayableAmount = (float)$dealerPayableAmount - (float)$debit_value;
        // }
        //End For Dealer

        // For Dealer
        $payment_voucher = PaymentVocuher::where('vendor_id', $stakeholders->id)->where('status', 0)->get();
        if (count($payment_voucher) > 0) {
            $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
            $vendorPayableAmount = (float)$vendorPayableAmount - (float)$debit_value;
        }

        // For cheque inactive
        // $payment_voucher = PaymentVocuher::where('vendor_id', $stakeholders->id)->where('status', 1)->where('cheque_status', 0)->get();

        // if (count($payment_voucher) > 0) {
        //     $debit_value = collect($payment_voucher)->sum('amount_to_be_paid');
        //     $vendorPayableAmount = (float)$vendorPayableAmount - (float)$debit_value;
        // }
        //End For Dealer

        // End of payment voucher details



        $total_payable_amount  = $customerPayableAmount  +  $dealerPayableAmount + $vendorPayableAmount;

        return response()->json([
            'success' => true,
            'unit_id' => $unit->id,
            'unit_type' => $unit->type->name,
            'unit_floor' => $unit->floor->name,
            'unit_name' => $unit->name,
            'customerApAccount' => $customerApAccount,
            'dealerApAccount' => $dealerApAccount,
            'vendorApAccount' => $vendorApAccount,
            'customerPayableAmount' => $customerPayableAmount,
            'dealerPayableAmount' => $dealerPayableAmount,
            'vendorPayableAmount' => $vendorPayableAmount,
            'total_payable_amount' => $total_payable_amount,
        ], 200);
    }

    public function getUnpaidInstallments(Request $request)
    {
        $sales_plan = SalesPlan::where('unit_id', $request->unit_id)->where('status', 1)->with('PaidorPartiallyPaidInstallments', 'unPaidInstallments', 'stakeholder')->first();
        $stakeholders = $sales_plan->stakeholder;
        // dd($stakeholders->country->name);
        $stakeholders->cnic = cnicFormat($stakeholders->cnic);
        $installmentFullyPaidUnderAmount = [];
        $installmentPartialyPaidUnderAmount = [];
        $calculate_amount = 0;
        $to_be_paid_calculate_amount = 0;
        $total_calculated_installments = [];
        $amount_to_be_paid = $request->amount;
        $total_installment_required_amount = 0;
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
                    'detail' => $installment->details,
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
                        'detail' => $installment->details,
                    ];
                }

                break;
            }
        }
        foreach ($sales_plan->unPaidInstallments as $installment) {
            if ($installment->status == 'partially_paid') {
                $total_installment_required_amount = $total_installment_required_amount +  $installment->remaining_amount;
            } else {
                $total_installment_required_amount = $total_installment_required_amount +  $installment->amount;
            }
        }

        $total_calculated_installments = array_merge($installmentFullyPaidUnderAmount, $installmentPartialyPaidUnderAmount);
        $amount_entered = (float)$request->amount;

        if ($amount_entered > $total_installment_required_amount) {
            return response()->json([
                'success' => false,
                'message' => 'Entered Amount is greater than Required Amount. Required Amount is ' . $total_installment_required_amount,
            ], 200);
        }

        return response()->json([
            'success' => true,
            'sales_plan' => $sales_plan,
            'unpaid_installments_to_be_paid' => $installmentFullyPaidUnderAmount,
            'unpaid_installments_to_be_partialy_paid' => $installmentPartialyPaidUnderAmount,
            'total_calculated_installments' => $total_calculated_installments,
            'total_installment_required_amount' => $total_installment_required_amount,
            'amount_to_be_paid' => $request->amount,
            'already_paid'  => $sales_plan->PaidorPartiallyPaidInstallments,
            'stakeholders'  => $stakeholders,
            'country'       => $stakeholders->country_id ? $stakeholders->country->name : '',
            'state'         => $stakeholders->state_id ? $stakeholders->state->name : '',
            'city'          => $stakeholders->city_id ? $stakeholders->city->name : '',
        ], 200);
    }


    public function printReceipt($site_id, $receipt_id, $template_id)
    {

        $receipt_data = Receipt::find($receipt_id);

        $template = ReceiptTemplate::find(decryptParams($template_id));

        $unit = Unit::find($receipt_data->unit_id);

        $preview_data = [
            'serial_no' => $receipt_data->serial_no,
            'unit_name' => $unit->name,
            'unit_type' => $unit->type->name,
            'unit_floor' => $unit->floor->name,
            'name' => $receipt_data->name,
            'cnic' => str_split($receipt_data->cnic),
            'mode_of_payment' => $receipt_data->mode_of_payment,
            'other_value' => $receipt_data->other_value,
            'pay_order' => $receipt_data->pay_order,
            'cheque_no' => $receipt_data->cheque_no,
            'online_instrument_no' => $receipt_data->online_instrument_no,
            'drawn_on_bank' => $receipt_data->drawn_on_bank,
            'transaction_date' => $receipt_data->created_date,
            'amount_in_numbers' => $receipt_data->amount_in_numbers,
            'amount_in_words' =>  numberToWords($receipt_data->amount_in_numbers),
            'purpose' => $receipt_data->purpose,
            'other_purpose' => $receipt_data->other_purpose,
            'installment_number' => str_replace(str_split('[]"'), '', $receipt_data->installment_number),
            'online_instrument_no' => $receipt_data->online_instrument_no,
        ];
        return view('app.sites.receipts.templates.' . $template->slug, compact('preview_data'));
    }


    public function ImportPreview(Request $request, $site_id)
    {
        try {
            $model = new TempReceipt();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx'
                ]);

                // $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error

                TempReceipt::query()->truncate();
                $import = new ReceiptsImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.receipts.storePreview', ['site_id' => $site_id]);
            } else {
                return Redirect::back()->withDanger('Select File to Import');
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            if (count($e->failures()) > 0) {
                $data = [
                    'site_id' => decryptParams($site_id),
                    'errorData' => $e->failures()
                ];
                return Redirect::back()->with(['data' => $e->failures()]);
            }
        }
    }
    public function storePreview(Request $request, $site_id)
    {
        $model = new TempReceipt();
        if ($model->count() == 0) {
            return redirect()->route('sites.receipts.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.No Record Found'));
        } else {
            $dataTable = new ImportReceiptsDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
            ];
            return $dataTable->with($data)->render('app.sites.receipts.importReceiptsPreview', $data);
        }
    }

    public function saveImport(Request $request, $site_id)
    {

        $validator = \Validator::make($request->all(), [
            'fields.*' => 'required',
        ], [
            'fields.*.required' => 'Must Select all Fields',
            'fields.*.distinct' => 'Field can not be duplicated',

        ]);

        $validator->validate();

        $this->receiptInterface->ImportReceipts($site_id);

        TempReceipt::query()->truncate();

        return redirect()->route('sites.receipts.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
    }
    public function getInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempData = (new TempReceipt())->find((int)$request->get('id'));

            switch ($field) {
                case 'unit_short_label':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|exists:App\Models\Unit,floor_unit_number',
                        ], [
                            'value' => 'Unit Does not Exists.'
                        ]);
                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempData->unit_short_label = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'stakeholder_cnic':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|exists:App\Models\Stakeholder,cnic',
                        ], [
                            'value' => 'Stakeholder Does not Exists.'
                        ]);
                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $tempData->stakeholder_cnic = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'total_price':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|gt:0',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempData->total_price = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'down_payment_total':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->down_payment_total =  $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }
                    break;

                case 'validity':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->validity = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'mode_of_payment':
                    $tempData->mode_of_payment = $request->get('value');
                    $tempData->save();

                    $values = ['cash' => 'Cash', 'cheque' => 'Cheque', 'online' => 'Online', 'other' => 'Other'];

                    $response =  view(
                        'app.components.input-select-fields',
                        [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'values' => $values,
                            'selectedValue' => $tempData->mode_of_payment
                        ]
                    )->render();
                    break;
                case 'cheque_no':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->cheque_no = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;
                case 'bank_name':
                    if ($request->get('updateValue') == 'true') {

                        if ($request->get('value') != "null" || $request->get('value') != "") {
                            $validator = \Validator::make($request->all(), [
                                'value' => 'required|exists:App\Models\Bank,slug',
                            ], [
                                'value' => 'Bank Name Does not Exists.'
                            ]);
                            if ($validator->fails()) {
                                return apiErrorResponse($validator->errors()->first('value'));
                            }
                        }
                        $tempData->bank_name = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;
                case 'bank_acount_number':
                    if ($request->get('updateValue') == 'true') {

                        if ($request->get('value') != "null" || $request->get('value') != "") {
                            $validator = \Validator::make($request->all(), [
                                'value' => 'required|exists:App\Models\Bank,account_number',
                            ], [
                                'value' => 'Bank Account Number Does not Exists.'
                            ]);
                            if ($validator->fails()) {
                                return apiErrorResponse($validator->errors()->first('value'));
                            }
                        }

                        $tempData->bank_acount_number = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;
                case 'online_transaction_no':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->online_transaction_no = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;
                case 'transaction_date':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->transaction_date = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;
                case 'other_payment_mode_value':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->other_payment_mode_value = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;
                case 'amount':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->amount = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;

                case 'installment_no':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->installment_no = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;
                case 'image_url':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->image_url = $request->get('value');
                        $tempData->save();

                        $response = view('app.components.unit-preview-cell', [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'inputtype' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    } else {
                        $response = view('app.components.text-number-field', [
                            'field' => $field,
                            'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                            'value' => $request->get('value')
                        ])->render();
                    }

                    break;
                case 'status':
                    $tempData->status = $request->get('value');
                    $tempData->save();
                    $values = ['active' => 'Active', 'inactive' => 'In Active', 'cancel' => 'Cancel'];

                    $response =  view(
                        'app.components.input-select-fields',
                        [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'values' => $values,
                            'selectedValue' => $tempData->status
                        ]
                    )->render();
                    break;
                default:
                    $response = view('app.components.text-number-field', [
                        'field' => $field,
                        'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                        'value' => $request->get('value')
                    ])->render();
                    break;
            }
            return apiSuccessResponse($response);
        } catch (Exception $ex) {
            return apiErrorResponse($ex->getMessage());
        }
    }

    public function revertPayment(Request $request, $site_id, $ids)
    {
        try {
            $site_id = decryptParams($site_id);
            if (!request()->ajax()) {
                // if ($request->has('chkRole')) {

                $record = $this->receiptInterface->revertPayment($site_id, $ids);

                if ($record) {
                    return redirect()->route('sites.receipts.index', ['site_id' => encryptParams($site_id)])->withSuccess('Data Reverted');
                } else {
                    return redirect()->route('sites.receipts.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.data_not_found'));
                }
                // }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.receipts.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }
}
