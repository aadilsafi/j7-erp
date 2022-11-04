<?php

namespace App\Http\Controllers;

use App\Models\SalesPlanInstallments;
use App\Models\TempSalePlanInstallment;
use Illuminate\Http\Request;

class SalesPlanImportController extends Controller
{

    public function ImportInstallmentPreview(Request $request, $site_id)
    {
        try {
            $model = new TempSalePlanInstallment();

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

                return redirect()->route('sites.floors.SalesPlanImport.storePreview', ['site_id' => $site_id]);
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
        $model = new TempSalePlan();
        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.No Record Found'));
        } else {
            $dataTable = new ImportSalesPlanDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
            ];
            return $dataTable->with($data)->render('app.sites.floors.units.sales-plan.import.importSalesPlanPreview', $data);
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

        $status  = [
            0 => 'pending',
            1 => 'approved',
            2 => 'disapproved',
            3 => 'cancelled',
        ];

        $validator->validate();

        $model = new TempSalePlan();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();

        foreach ($tempdata as $key => $items) {
            foreach ($tempCols as $k => $field) {
                $data[$key][$field] = $items[$tempCols[$k]];
            }

            // $data[$key]['site_id'] = decryptParams($site_id);
            $data[$key]['user_id'] = Auth::user()->id;
            $data[$key]['comments'] = $data[$key]['comment'];

            $data[$key]['status'] = array_search($data[$key]['status'], $status);

            $unit = Unit::where('floor_unit_number', $data[$key]['unit_short_label'])->first();
            $data[$key]['unit_id'] = $unit->id;

            $stakeholder = Stakeholder::where('cnic', $data[$key]['stakeholder_cnic'])->first();
            $data[$key]['stakeholder_id'] = $stakeholder->id;
            $data[$key]['stakeholder_data'] = json_encode($stakeholder);

            $leadSource = LeadSource::where('name', Str::title($data[$key]['lead_source']))->first();
            if ($leadSource) {
                $data[$key]['lead_source_id'] = $leadSource->id;
            } else {
                $leadSource = LeadSource::create([
                    'site_id' => decryptParams($site_id),
                    'name' => $data[$key]['lead_source']
                ]);
                $data[$key]['lead_source_id'] = $leadSource->id;
            }

            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();

            unset($data[$key]['stakeholder_cnic']);
            unset($data[$key]['unit_short_label']);
            unset($data[$key]['lead_source']);
            unset($data[$key]['comment']);

            // dd($data);

            $unit = SalesPlan::insert($data[$key]);
        }

        TempSalePlan::query()->truncate();

        return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
    }

    public function getInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempData = (new TempSalePlan())->find((int)$request->get('id'));

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

                case 'unit_price':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|gt:0',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempData->unit_price = $request->get('value');
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

                case 'discount_percentage':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->discount_percentage = $request->get('value');
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


                case 'discount_total':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->discount_total = $request->get('value');
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

                case 'down_payment_percentage':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->down_payment_percentage =  $request->get('value');
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

                case 'lead_source':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->lead_source = $request->get('value');
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

                case 'status':
                    $tempData->status = $request->get('value');
                    $tempData->save();

                    $values = ['pending' => 'Pending', 'approved' => 'Approved', 'disapproved' => 'Disapproved', 'cancelled' => 'Cancelled'];
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
                case 'comment':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->comment = $request->get('value');
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
                case 'approved_date':
                    if ($request->get('updateValue') == 'true') {

                        $tempData->approved_date = $request->get('value');
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
}
