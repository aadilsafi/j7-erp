<?php

namespace App\Http\Controllers;

use App\DataTables\AdditionalCostsDataTable;
use App\DataTables\ImportAdditionalCostsDataTable;
use App\Services\CustomFields\CustomFieldInterface;
use Illuminate\Http\Request;
use App\Http\Requests\additionalCosts\{
    storeRequest as additionalCostStoreRequest,
    updateRequest as additionalCostUpdateRequest
};
use App\Imports\AdditionalCostsImport;
use App\Models\AdditionalCost;
use App\Models\TempAdditionalCost;
use App\Services\AdditionalCosts\AdditionalCostInterface;
use Exception;
use File;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\HeadingRowImport;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Redirect;
use Illuminate\Support\Facades\Storage;
use Str;

class AdditionalCostController extends Controller
{
    private $additionalCostInterface;

    public function __construct(AdditionalCostInterface $additionalCostInterface, CustomFieldInterface $customFieldInterface)
    {
        $this->additionalCostInterface = $additionalCostInterface;
        $this->customFieldInterface = $customFieldInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(AdditionalCostsDataTable $dataTable, $site_id)
    {
        return $dataTable->render('app.additional-costs.index', ['site_id' => $site_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        if (!request()->ajax()) {

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->additionalCostInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site_id' => $site_id,
                'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                'customFields' => $customFields
            ];
            return view('app.additional-costs.create', $data);
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
    public function store(additionalCostStoreRequest $request, $site_id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->all();
                $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->additionalCostInterface->model()));

                $record = $this->additionalCostInterface->store($site_id, $inputs, $customFields);
                return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $site_id, $id)
    {
        try {
            $additionalCost = $this->additionalCostInterface->getById($site_id, $id);
            if ($additionalCost && !empty($additionalCost)) {
                $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->additionalCostInterface->model()));
                $customFields = collect($customFields)->sortBy('order');
                $customFields = generateCustomFields($customFields, true, $additionalCost->id);

                $data = [
                    'site_id' => $site_id,
                    'additionalCost' => $additionalCost,
                    'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                    'customFields' => $customFields
                ];

                return view('app.additional-costs.edit', $data);
            }

            return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(additionalCostUpdateRequest $request, $site_id, $id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->all();
                // return [$site_id, $id, $inputs];
                $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->additionalCostInterface->model()));

                $record = $this->additionalCostInterface->update($site_id, $inputs, $id, $customFields);
                return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function destroySelected(Request $request, $site_id)
    {
        try {
            if (!request()->ajax()) {
                // dd($request->all());
                if ($request->has('chkAdditionalCost')) {

                    $record = $this->additionalCostInterface->destroy($site_id, encryptParams($request->chkAdditionalCost));

                    if ($record) {
                        return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_deleted'));
                    } else {
                        return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function getInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempAdcosts = (new TempAdditionalCost())->find((int)$request->get('id'));

            switch ($field) {
                case 'additional_costs_name':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|unique:additional_costs,slug',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $validator2 = \Validator::make($request->all(), [
                            'value' => [
                                Rule::unique('temp_additional_costs', 'additional_costs_name')->ignore($request->get('id'))
                            ],
                        ]);

                        if ($validator2->fails()) {
                            return apiErrorResponse($validator2->errors()->first('value'));
                        }


                        $tempAdcosts->additional_costs_name = $request->get('value');

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

                case 'site_percentage':
                    if ($request->get('updateValue') == 'true') {

                        $tempAdcosts->site_percentage = $request->get('value');

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

                case 'floor_percentage':
                    if ($request->get('updateValue') == 'true') {

                        $tempAdcosts->floor_percentage = $request->get('value');

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

                case 'unit_percentage':
                    if ($request->get('updateValue') == 'true') {

                        $tempAdcosts->unit_percentage = $request->get('value');

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

                case 'parent_type_name':
                    if ($request->get('updateValue') == 'true') {

                        $tempAdcosts->parent_type_name = $request->get('value');

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

                case 'is_sub_types':
                    if ($request->get('updateValue') == 'true') {

                        $tempAdcosts->is_sub_types =  $request->get('value');
                        $tempAdcosts->save();

                        $values = ['no' => 'No', 'yes' => 'Yes'];
                        $response =  view(
                            'app.components.input-select-fields',
                            [
                                'id' => $request->get('id'),
                                'field' => $field,
                                'values' => $values,
                                'selectedValue' => $request->get('value')
                            ]
                        )->render();
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
            $tempAdcosts->save();
            return apiSuccessResponse($response);
        } catch (Exception $ex) {
            return apiErrorResponse($ex->getMessage());
        }
    }

    public function ImportPreview(Request $request, $site_id)
    {

        try {
            $model = new TempAdditionalCost();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx'
                ]);

                $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error
                TempAdditionalCost::query()->truncate();
                $import = new AdditionalCostsImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.additional-costs.storePreview', ['site_id' => $site_id]);
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
        $model = new TempAdditionalCost();
        if ($model->count() == 0) {
            return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.No Record Found'));
        } else {
            $required = [
                'additional_costs_name',
                'is_sub_types',
                'parent_type_name'
            ];
            $dataTable = new ImportAdditionalCostsDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
                'required_fields' => $required,
            ];
            return $dataTable->with($data)->render('app.additional-costs.importAdcostsPreview', $data);
        }
    }

    public function saveImport(Request $request, $site_id)
    {
        // $validator = \Validator::make($request->all(), [
        //     'fields.*' => 'required',
        // ], [
        //     'fields.*.required' => 'Must Select all Fields',
        //     'fields.*.distinct' => 'Field can not be duplicated',

        // ]);

        // $validator->validate();

        $model = new TempAdditionalCost();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();

        foreach ($tempdata as $key => $items) {
            foreach ($tempCols as $k => $field) {

                $data[$key][$field] = $items[$tempCols[$k]];
            }

            $data[$key]['site_id'] = decryptParams($site_id);
            $data[$key]['slug'] = $data[$key]['additional_costs_name'];
            $data[$key]['name'] = Str::title(str_replace('-', ' ', $data[$key]['additional_costs_name']));


            if ($data[$key]['is_sub_types'] == 'yes' && $data[$key]['parent_type_name'] != null) {
                $parent = AdditionalCost::where('slug', $data[$key]['parent_type_name'])->first();
                if ($parent) {
                    $data[$key]['parent_id'] = $parent->id;
                } else {
                    $data[$key]['parent_id'] = 0;
                }
            } else {
                $data[$key]['parent_id'] = 0;
            }
            if ($data[$key]['site_percentage'] != 0) {
                $data[$key]['applicable_on_site'] = true;
            }
            if ($data[$key]['unit_percentage'] != 0) {
                $data[$key]['applicable_on_unit'] = true;
            }
            if ($data[$key]['floor_percentage'] != 0) {
                $data[$key]['applicable_on_floor'] = true;
            }

            $data[$key]['is_imported'] = true;
            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();

            unset($data[$key]['additional_costs_name']);
            unset($data[$key]['parent_type_name']);
            unset($data[$key]['is_sub_types']);

            $cost = AdditionalCost::insert($data[$key]);
        }

        TempAdditionalCost::query()->truncate();

        return redirect()->route('sites.additional-costs.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
    }

    public function downloadSample($site_id, $order)
    {
        $names = [
            'CompanyStakeholdersImport',
            'IndividualStakeholdersImport',
            'StakeholdersKinsImport',
            'StakeholdersContacts',
            'FloorsSample',
            'UnitsTypesSample',
            'AdditionalCostsSample',
            'UnitsSample',
            'SalesPlansImport',
            'SalesPlanAdditionalCostsImport',
            'SalesPlanInstallmentsImport',
            'BanksImport',
            'ReceiptsImport',
            'FilesImport',
            'FilesStakeholderConatcts',
            'JournalVoucherSample'
        ];

        $path = public_path('app-assets/ImportSamples/' . $order . '-' . $names[$order] . '.xlsx');

        return response()->download($path);
    }
}
