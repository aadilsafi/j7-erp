<?php

namespace App\Http\Controllers;

use App\DataTables\ImportSalesPlanAdCostsDataTable;
use App\DataTables\ImportSalesPlanInstallmentsDataTable;
use App\Imports\SalesPlanAdditionalCostsImport;
use App\Imports\SalesPlanInstallmentsImport;
use App\Models\AdditionalCost;
use App\Models\SalesPlan;
use App\Models\SalesPlanAdditionalCost;
use App\Models\SalesPlanInstallments;
use App\Models\Stakeholder;
use App\Models\TempSalePlanInstallment;
use App\Models\TempSalesPlanAdditionalCost;
use App\Models\Unit;
use DB;
use Exception;
use Illuminate\Http\Request;
use Redirect;
use Str;
use App\Services\{
    SalesPlan\Interface\SalesPlanInterface,
    Stakeholder\Interface\StakeholderInterface,
};

class SalesPlanImportController extends Controller
{
    private $salesPlanInterface;

    public function __construct(
        SalesPlanInterface $salesPlanInterface,
    ) {
        $this->salesPlanInterface = $salesPlanInterface;
    }
    // Sales Plan Additional Costs
    public function ImportPreviewAdcosts(Request $request, $site_id)
    {
        try {
            $model = new TempSalesPlanAdditionalCost();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx'
                ]);

                // $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error

                TempSalesPlanAdditionalCost::query()->truncate();
                $import = new SalesPlanAdditionalCostsImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.floors.spadcostsImport.storePreview', ['site_id' => $site_id]);
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
    public function storePreviewAdcosts(Request $request, $site_id)
    {
        $model = new TempSalesPlanAdditionalCost();
        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.No Record Found'));
        } else {
            $dataTable = new ImportSalesPlanAdCostsDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
            ];
            return $dataTable->with($data)->render('app.sites.floors.units.sales-plan.import.importSalesPlanAdcostsPreview', $data);
        }
    }

    public function saveImportAdcosts(Request $request, $site_id)
    {


        $model = new TempSalesPlanAdditionalCost();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();

        foreach ($tempdata as $key => $items) {
            foreach ($tempCols as $k => $field) {
                $data[$key][$field] = $items[$tempCols[$k]];
            }

            // $data[$key]['site_id'] = decryptParams($site_id);

            $stakeholderId = Stakeholder::select('id')->where('cnic', $data[$key]['stakeholder_cnic'])->first();
            $unitId = Unit::select('id')->where('floor_unit_number', $data[$key]['unit_short_label'])->first();

            $salePlan = SalesPlan::where('stakeholder_id', $stakeholderId->id)
                ->where('unit_id', $unitId->id)
                ->where('total_price', $data[$key]['total_price'])
                ->where('down_payment_total', $data[$key]['down_payment_total'])
                ->where('validity', $data[$key]['validity'])
                ->first();

            $adCost = AdditionalCost::where('slug', $data[$key]['additional_costs_name'])->first();

            $data[$key]['sales_plan_id'] = $salePlan->id;
            $data[$key]['additional_cost_id'] = $adCost->id;
            $data[$key]['amount'] = $data[$key]['total_amount'];
            $data[$key]['is_imported'] = true;

            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();

            unset($data[$key]['unit_short_label']);
            unset($data[$key]['stakeholder_cnic']);
            unset($data[$key]['total_price']);
            unset($data[$key]['total_amount']);
            unset($data[$key]['down_payment_total']);
            unset($data[$key]['validity']);
            unset($data[$key]['additional_costs_name']);

            $spAdCosts = SalesPlanAdditionalCost::insert($data[$key]);
        }

        TempSalesPlanAdditionalCost::query()->truncate();

        return redirect()->route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams(decryptParams($site_id)), 'floor_id' => encryptParams(0), 'unit_id' => encryptParams(0)])->withSuccess('Data Imported!');
    }
    public function getInputAdcosts(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempData = (new TempSalesPlanAdditionalCost())->find((int)$request->get('id'));

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

                case 'additional_costs_name':
                    if ($request->get('updateValue') == 'true') {

                        if ($request->get('value') != "null") {
                            $validator = \Validator::make($request->all(), [
                                'value' => 'required|exists:App\Models\AdditionalCost,slug',
                            ], [
                                'value' => 'Additional Costs Does not Exists.'
                            ]);
                            if ($validator->fails()) {
                                return apiErrorResponse($validator->errors()->first('value'));
                            }
                        }

                        $tempData->additional_costs_name = $request->get('value');
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
                case 'percentage':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->percentage = $request->get('value');
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
                case 'total_amount':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->total_amount = $request->get('value');
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


    //sales Plan Installments
    public function ImportPreviewinstallments(Request $request, $site_id)
    {
        try {
            $model = new TempSalesPlanAdditionalCost();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx'
                ]);

                // $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error

                TempSalePlanInstallment::query()->truncate();
                $import = new SalesPlanInstallmentsImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.floors.spInstallmentsImport.storePreviewInstallments', ['site_id' => $site_id]);
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
    public function storePreviewInstallments(Request $request, $site_id)
    {
        $model = new TempSalePlanInstallment();
        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.No Record Found'));
        } else {
            $dataTable = new ImportSalesPlanInstallmentsDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
            ];
            return $dataTable->with($data)->render('app.sites.floors.units.sales-plan.import.importspInstallmentsPreview', $data);
        }
    }
    public function saveImportInstallments(Request $request, $site_id)
    {

        $salesPlansIds = [];

        DB::transaction(function () use ($site_id, $request, &$salesPlansIds) {

            $model = new TempSalePlanInstallment();
            $tempdata = $model->cursor();
            $tempCols = $model->getFillable();
            foreach ($tempdata as $key => $items) {
                foreach ($tempCols as $k => $field) {
                    $data[$key][$field] = $items[$tempCols[$k]];
                }

            
                $salePlan = SalesPlan::where('doc_no', $data[$key]['sales_plan_doc_no'])
                    ->first();

                $salesPlansIds[$salePlan->id] = $salePlan->id;

                $data[$key]['sales_plan_id'] = $salePlan->id;

                if ($data[$key]['installment_no'] == 0) {
                    $data[$key]['details'] = Str::title($data[$key]['type']);
                } else {
                    if ($data[$key]['type'] == 'additional_expense') {
                        $data[$key]['details'] = Str::title(str_replace('-', ' ', $data[$key]['label']));
                    } else {
                        $data[$key]['details'] = (englishCounting($data[$key]['installment_no'])) . ' ' . Str::title($data[$key]['type']);
                    }
                }
                $data[$key]['date'] = $data[$key]['due_date'];

                $data[$key]['amount'] = $data[$key]['total_amount'];
                $data[$key]['installment_order'] = $data[$key]['installment_no'];
                $data[$key]['paid_amount'] = 0;
                $data[$key]['remaining_amount'] = $data[$key]['total_amount'];
                $data[$key]['status'] = 'unpaid';

                $data[$key]['is_imported'] = true;

                $data[$key]['created_at'] = now();
                $data[$key]['updated_at'] = now();

                unset($data[$key]['sales_plan_doc_no']);
                unset($data[$key]['stakeholder_cnic']);
                unset($data[$key]['total_price']);
                unset($data[$key]['total_amount']);
                unset($data[$key]['down_payment_total']);
                unset($data[$key]['validity']);
                unset($data[$key]['due_date']);
                unset($data[$key]['installment_no']);
                unset($data[$key]['label']);

                $spInstallment = SalesPlanInstallments::insert($data[$key]);
            }

            TempSalePlanInstallment::query()->truncate();
        });

        foreach ($salesPlansIds as $id) {
            $saleplan = SalesPlan::find($id);
            $this->salesPlanInterface->generatePDF($saleplan, 'investment_plan');
            if ($saleplan->status == '1') {
                $this->salesPlanInterface->generatePDF($saleplan, 'payment_plan');
            }
        }
        return redirect()->route('sites.floors.units.sales-plans.index', ['site_id' => encryptParams(decryptParams($site_id)), 'floor_id' => encryptParams(0), 'unit_id' => encryptParams(0)])->withSuccess('Sales Plan Imported!');
    }
    public function getInputInstallments(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempData = (new TempSalePlanInstallment())->find((int)$request->get('id'));

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

                case 'type':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->type = $request->get('value');
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
                case 'label':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->label = $request->get('value');
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
                case 'total_amount':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->total_amount = $request->get('value');
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
                case 'paid_amount':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->paid_amount = $request->get('value');
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
                case 'remaining_amount':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->remaining_amount = $request->get('value');
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
                case 'due_date':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->due_date = $request->get('value');
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
                case 'last_paid_at':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->last_paid_at = $request->get('value');
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
                case 'remarks':
                    $tempData->remarks = $request->get('value');
                    $tempData->save();

                    $values = ['paid' => 'Paid', 'unpaid' => 'Un Paid', 'partially-paid' => 'Partially Paid'];

                    $response =  view(
                        'app.components.input-select-fields',
                        [
                            'id' => $request->get('id'),
                            'field' => $field,
                            'values' => $values,
                            'selectedValue' => $tempData->remarks
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
}
