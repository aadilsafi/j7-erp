<?php

namespace App\Http\Controllers;

use App\DataTables\{ImportUnitsDataTable, UnitsDataTable, UnitsPreviewDataTable};
use App\Models\{AdditionalCost, Floor, Site, Status, TempUnit, Type, Unit};
use App\Services\Interfaces\{UnitInterface, UnitTypeInterface, UserBatchInterface};
use Illuminate\Http\Request;
use App\Http\Requests\units\{
    fabstoreRequest,
    storeRequest as unitStoreRequest,
    updateRequest as unitUpdateRequest
};
use App\Imports\UnitsImport;
use App\Services\AdditionalCosts\AdditionalCostInterface;
use App\Services\CustomFields\CustomFieldInterface;

use App\Utils\Enums\{UserBatchActionsEnum, UserBatchStatusEnum};
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Redirect;
use Str;

class UnitController extends Controller
{
    private $unitInterface;
    private $additionalCostInterface;
    private $unitTypeInterface;
    private $userBatchInterface;

    public function __construct(
        UnitInterface $unitInterface,
        AdditionalCostInterface $additionalCostInterface,
        UnitTypeInterface $unitTypeInterface,
        UserBatchInterface $userBatchInterface,
        CustomFieldInterface $customFieldInterface
    ) {
        $this->unitInterface = $unitInterface;
        $this->additionalCostInterface = $additionalCostInterface;
        $this->unitTypeInterface = $unitTypeInterface;
        $this->userBatchInterface = $userBatchInterface;
        $this->customFieldInterface = $customFieldInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(UnitsDataTable $dataTable, $site_id, $floor_id)
    {
        $nonActiveUnits = (new Unit())->where('active', false)->where('floor_id', decryptParams($floor_id))->get();

        if (!empty($nonActiveUnits) && count($nonActiveUnits) > 0) {
            return redirect()->route('sites.floors.units.preview', ['site_id' => encryptParams(decryptParams($site_id)), 'floor_id' => encryptParams(decryptParams($floor_id))]);
        }

        $data = [
            'site' => (new Site())->find(decryptParams($site_id)),
            'floor' => (new Floor())->find(decryptParams($floor_id))
        ];

        return $dataTable->with($data)->render('app.sites.floors.units.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id, $floor_id)
    {
        if (!request()->ajax()) {

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->unitInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site' => (new Site())->find(decryptParams($site_id)),
                'floor' => (new Floor())->find(decryptParams($floor_id)),
                'siteConfiguration' => getSiteConfiguration($site_id),
                'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                'types' => $this->unitTypeInterface->getAllWithTree(decryptParams($site_id)),
                'statuses' => (new Status())->all(),
                'max_unit_number' => getMaxUnitNumber(decryptParams($floor_id)) + 1,
                'customFields' => $customFields

            ];

            return view('app.sites.floors.units.create', $data);
        } else {
            abort(403);
        }
    }

    function createfabUnit($site_id, $floor_id)
    {

        if (!request()->ajax()) {
            $data = [
                'site' => (new Site())->find(decryptParams($site_id)),
                'floor' => (new Floor())->find(decryptParams($floor_id)),
                'siteConfiguration' => getSiteConfiguration($site_id),
                'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                'units' => (new Unit())->where('status_id', 1)->where('parent_id', 0)->where('has_sub_units', false)->where('floor_id', decryptParams($floor_id))->with('status', 'type')->get(),
                'types' => $this->unitTypeInterface->getAllWithTree(decryptParams($site_id)),
                'statuses' => (new Status())->all(),
                'emptyUnit' => $this->unitInterface->getEmptyInstance(),
            ];

            return view('app.sites.floors.units.fab-units.create', $data);
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
    public function store(unitStoreRequest $request, $site_id, $floor_id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                if ($inputs['add_bulk_unit']) {
                    $record = $this->unitInterface->storeInBulk($site_id, $floor_id, $inputs);
                    return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id])->withSuccess('Unit(s) will be contructed shortly!');
                } else {
                    $record = $this->unitInterface->store($site_id, decryptParams($floor_id), $inputs);
                }

                return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id,])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            Log::error($ex->getLine() . " Message => " . $ex->getMessage());
            return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id,])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function storefabUnit(fabstoreRequest $request, $site_id, $floor_id)
    {

        try {
            if (!request()->ajax()) {
                // $inputs = $request->validated();
                $inputs = $request->all();

                $record = $this->unitInterface->storeFabUnit($site_id, $request->floor_id, $inputs);

                return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id,])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            Log::error($ex->getLine() . " Message => " . $ex->getMessage());
            return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id,])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
    public function edit(Request $request, $site_id, $floor_id, $id)
    {
        try {
            $unit = $this->unitInterface->getById($site_id, $floor_id, $id, ['salesPlan']);
            if ($unit && !empty($unit)) {
                // dd($unit);
                $data = [
                    'site' => (new Site())->find(decryptParams($site_id)),
                    'floor' => (new Floor())->find(decryptParams($floor_id)),
                    'siteConfiguration' => getSiteConfiguration(encryptParams(decryptParams($site_id))),
                    'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                    'types' => $this->unitTypeInterface->getAllWithTree(decryptParams($site_id)),
                    'statuses' => (new Status())->all(),
                    'unit' => $unit,
                ];

                return view('app.sites.floors.units.edit', $data);
            }

            return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(unitUpdateRequest $request, $site_id, $floor_id, $id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->unitInterface->update($site_id, $floor_id, $id, $inputs);

                return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function destroySelected(Request $request, $site_id, $floor_id)
    {
        try {
            if (!request()->ajax()) {
                if ($request->has('chkTableRow')) {

                    $record = $this->unitInterface->destroy($site_id, $floor_id, encryptParams($request->chkTableRow));

                    if ($record) {
                        return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id,])->withSuccess(__('lang.commons.data_deleted'));
                    } else {
                        return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id,])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id,])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function preview(UnitsPreviewDataTable $dataTable, $site_id, $floor_id)
    {
        $data = [
            'site' => (new Site())->find(decryptParams($site_id)),
            'floor' => (new Floor())->find(decryptParams($floor_id)),
            'types' => $this->unitTypeInterface->getAllWithTree(decryptParams($site_id)),
        ];
        // $data = [
        //     'site' => (new Site())->find(1),
        //     'floor' => (new Floor())->find(2),
        //     'types' => $this->unitTypeInterface->getAllWithTree(),
        // ];

        return $dataTable->with($data)->render('app.sites.floors.units.preview', $data);
    }

    public function saveChanges(Request $request, $site_id, $floor_id)
    {

        try {
            (new Unit())->where('floor_id', decryptParams($floor_id))->update([
                'active' => true
            ]);
            return redirect()->route('sites.floors.units.index', ['site_id' => encryptParams(decryptParams($site_id)), 'floor_id' => encryptParams(decryptParams($floor_id))]);
        } catch (Exception $th) {
            return redirect()->back();
        }
    }

    public function updateUnitName(Request $request)
    {
        try {
            $unit = (new Unit())->find((int)$request->get('id'));
            $value = $request->get('value');
            $field = $request->get('field');

            //Validation
            switch ($field) {
                case 'name':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'name' => 'required|string|max:255',
                        ]
                    );
                    break;

                case 'width':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'width' => 'required|numeric',
                        ]
                    );
                    break;

                case 'length':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'length' => 'required|numeric',
                        ]
                    );
                    break;

                case 'net_area':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'net_area' => 'required|numeric',
                        ]
                    );
                    break;

                case 'gross_area':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'gross_area' => 'required|numeric|gte:' . $unit->net_area,
                        ]
                    );
                    break;

                case 'price_sqft':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'price_sqft' => 'required|numeric',
                        ]
                    );
                    break;

                case 'is_corner':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'is_corner' => 'required|boolean|in:0,1',
                        ]
                    );
                    break;

                case 'is_facing':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'is_facing' => 'required|boolean|in:0,1',
                        ]
                    );
                    break;

                case 'facing_id':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'facing_id' => 'required_if:' . $unit->is_facing . ',1|integer',
                        ]
                    );
                    break;

                case 'type_id':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'type_id' => 'required|integer',
                        ]
                    );
                    break;

                case 'status_id':
                    $validateRequest = Validator::make(
                        $request->get('fieldsData'),
                        [
                            'status_id' => 'required|integer',
                        ]
                    );
                    break;

                default:
                    return response()->json([
                        'status' => false,
                        'message' => 'Bad Request',
                    ]);
            }

            if ($validateRequest->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => $validateRequest->errors(),
                ]);
            }

            $facing = '';
            if ($field == 'is_corner') {
                $unit->is_corner = !($unit->is_corner);
            } elseif ($field == 'is_facing') {
                $unit->is_facing = !($unit->is_facing);
                if ($unit->is_facing) {
                    if ($unit->facing_id == null) {
                        $unit->facing_id = 1;
                    }
                    $facing = 'yes';
                } else {
                    $facing = 'no';
                }
            } elseif ($field == 'gross_area' || $field == 'price_sqft') {
                $unit->{$field} = $value;
                $unit->total_price = $unit->price_sqft * $unit->gross_area;
            } else {
                $unit->{$field} = $value;
            }
            $unit->save();
            return response()->json([
                'status' => true,
                'message' => "Updated Successfully",
                'data' => [
                    'facing' => $facing
                ]
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'status' => false,
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getUnitInput(Request $request)
    {
        try {
            $site_id = $request->get('site_id');
            $field = $request->get('field');
            $unit = (new Unit())->find((int)$request->get('id'));

            switch ($field) {
                case 'status_id':
                    $statuses = (new Status())->all();
                    $response = view('app.components.select-dropdown', [
                        'id' => $request->get('id'), 'field' => $field, 'data_id' => $unit->status->id,
                        'type' => 'status', 'value' => $unit->status->name, 'values' => $statuses
                    ])->render();
                    break;

                case 'type_id':
                    $types = $this->unitTypeInterface->getAllWithTree(decryptParams($site_id));
                    $response = view('app.components.select-dropdown', [
                        'id' => $request->get('id'), 'field' => $field, 'data_id' => $unit->type->id,
                        'type' => 'type', 'value' => $unit->type->name, 'values' => $types
                    ])->render();
                    break;

                case 'facing_id':
                    $additionalCosts = $this->additionalCostInterface->getAllWithTree(encryptParams($unit->floor->site->id));
                    $response = view('app.components.select-dropdown', [
                        'id' => $request->get('id'), 'field' => $field, 'data_id' => $unit->facing->id,
                        'type' => 'facing', 'value' => $unit->facing->name, 'values' => $additionalCosts
                    ])->render();
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

    public function drawFacingField(Request $request)
    {
        try {
            $unit = (new Unit())->find((int)$request->get('id'));
            $response = view('app.components.checkbox', [
                'id' => $unit->id,
                'data' => $unit,
                'field' => 'is_facing',
                'is_true' => $unit->is_facing
            ])->render();

            return apiSuccessResponse($response);
        } catch (Exception $ex) {
            return apiErrorResponse($ex->getMessage());
        }
    }


    public function getUnitData(Request $request, $site_id, $floor_id)
    {

        $unit = (new Unit())->find($request->unit_id);
        return response()->json([
            'success' => true,
            'unit' => $unit,
            'max_unit_number' => getMaxUnitNumber($unit->floor_id) + 1,
            'floor_name' => $unit->floor->name,
            'remaing_gross' => $unit->gross_area,
            'remaing_net' => $unit->net_area,
        ], 200);
    }


    public function getInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempUnit = (new TempUnit())->find((int)$request->get('id'));

            switch ($field) {
                case 'floor_short_label':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|exists:App\Models\Floor,short_label',
                        ], [
                            'value' => 'Floor Does not Exists.'
                        ]);
                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempUnit->floor_short_label = $request->get('value');
                        $tempUnit->save();

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

                case 'name':
                    if ($request->get('updateValue') == 'true') {

                        $tempUnit->name = $request->get('value');
                        $tempUnit->save();

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

                case 'unit_short_label':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|unique:units,floor_unit_number',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $validator2 = \Validator::make($request->all(), [
                            'value' => [
                                Rule::unique('temp_units', 'unit_short_label')->ignore($request->get('id'))
                            ],
                        ]);

                        if ($validator2->fails()) {
                            return apiErrorResponse($validator2->errors()->first('value'));
                        }
                        $tempUnit->unit_short_label = $request->get('value');
                        $tempUnit->save();

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

                case 'net_area':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|gt:0',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempUnit->net_area = $request->get('value');
                        $tempUnit->save();

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

                case 'gross_area':
                    if ($request->get('updateValue') == 'true') {
                        $request['net_area'] = $tempUnit->net_area;
                        $validator = \Validator::make($request->all(), [
                            'net_area' => 'sometimes',
                            'value' => 'required|gte:net_area',
                        ], [
                            'value.gte' =>  'Gross Area Must be greater then Net area'
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $tempUnit->gross_area = $request->get('value');
                        $tempUnit->save();

                        $total_price = $tempUnit->gross_area * $tempUnit->price_sqft;
                        $tempUnit->total_price = $total_price;
                        $tempUnit->save();

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


                case 'price_sqft':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|gt:0',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempUnit->price_sqft = $request->get('value');
                        $tempUnit->save();

                        $total_price = $tempUnit->gross_area * $tempUnit->price_sqft;
                        $tempUnit->total_price = $total_price;
                        $tempUnit->save();

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

                case 'unit_type_slug':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|exists:App\Models\Type,slug',
                        ], [
                            'value' => 'Type Does not Exists.'
                        ]);
                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $tempUnit->unit_type_slug =  $request->get('value');
                        $tempUnit->save();

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

                case 'parent_unit_short_label':
                    if ($request->get('updateValue') == 'true') {

                        if ($request->get('value') != 'NULL') {
                            $validator = \Validator::make($request->all(), [
                                'value' => 'required|exists:App\Models\Unit,floor_unit_number',
                            ], [
                                'value' => 'Unit Does not Exists.'
                            ]);
                            if ($validator->fails()) {
                                $validator = \Validator::make($request->all(), [
                                    'value' => 'required|exists:App\Models\TempUnit,unit_short_label',
                                ], [
                                    'value' => 'Unit Does not Exists.'
                                ]);

                                if ($validator->fails()) {
                                    return apiErrorResponse($validator->errors()->first('value'));
                                }
                            }
                        }

                        $tempUnit->parent_unit_short_label =  $request->get('value');
                        $tempUnit->save();

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

                case 'width':
                    if ($request->get('updateValue') == 'true') {

                        $tempUnit->width = $request->get('value');
                        $tempUnit->save();

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

                case 'length':
                    if ($request->get('updateValue') == 'true') {

                        $tempUnit->length = $request->get('value');
                        $tempUnit->save();

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
                    $total_price = $tempUnit->gross_area * $tempUnit->price_sqft;

                    $tempUnit->total_price = $total_price;
                    $tempUnit->save();
                    $response = view('app.components.unit-preview-cell', [
                        'id' => $request->get('id'),
                        'field' => $field,
                        'inputtype' => $request->get('inputtype'),
                        'value' => $total_price
                    ])->render();

                    break;

                case 'status':
                    if ($request->get('updateValue') == 'true') {

                        $tempUnit->status = $request->get('value');
                        $tempUnit->save();

                        $values = ['open' => 'Open', 'sold' => 'Sold', 'token' => 'Token', 'partial-paid' => 'Partial Paid', 'hold' => 'Hold'];

                        $response =  view(
                            'app.components.input-select-fields',
                            [
                                'id' => $request->get('id'),
                                'field' => $field,
                                'values' => $values,
                                'selectedValue' => $tempUnit->status
                            ]
                        )->render();
                    }

                    break;

                case 'is_corner':
                    if ($request->get('updateValue') == 'true') {

                        $tempUnit->status = $request->get('value');
                        $tempUnit->save();

                        $values = ['FALSE' => 'No', 'TRUE' => 'Yes'];

                        $response =  view(
                            'app.components.input-select-fields',
                            [
                                'id' => $request->get('id'),
                                'field' => $field,
                                'values' => $values,
                                'selectedValue' => $tempUnit->status
                            ]
                        )->render();
                    }

                    break;

                case 'is_facing':
                    if ($request->get('updateValue') == 'true') {

                        $tempUnit->status = $request->get('value');
                        $tempUnit->save();

                        $values = ['FALSE' => 'No', 'TRUE' => 'Yes'];

                        $response =  view(
                            'app.components.input-select-fields',
                            [
                                'id' => $request->get('id'),
                                'field' => $field,
                                'values' => $values,
                                'selectedValue' => $tempUnit->status
                            ]
                        )->render();
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

                        $tempUnit->additional_costs_name = $request->get('value');
                        $tempUnit->save();

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

    public function ImportPreview(Request $request, $site_id)
    {

        try {
            $model = new TempUnit();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx'
                ]);

                // $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error

                TempUnit::query()->truncate();
                $import = new UnitsImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.floors.unitsImport.storePreview', ['site_id' => $site_id]);
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
        $model = new TempUnit();
        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.No Record Found'));
        } else {
            $required = [
                'floor_short_label',
                'name',
                'unit_short_label',
                'gross_area',
                'price_sqft',
                'unit_type_slug',
                'parent_unit_short_label'
            ];

            $dataTable = new ImportUnitsDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
                'required_fields' => $required,
            ];
            return $dataTable->with($data)->render('app.sites.floors.units.importUnitsPreview', $data);
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

        $model = new TempUnit();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();

        foreach ($tempdata as $key => $items) {
            foreach ($tempCols as $k => $field) {
                $data[$key][$field] = $items[$tempCols[$k]];
            }

            // $data[$key]['site_id'] = decryptParams($site_id);
            $data[$key]['active'] = true;

            $floor = Floor::where('short_label', $data[$key]['floor_short_label'])->first();
            $data[$key]['floor_id'] = $floor->id;

            if (strtolower($data[$key]['parent_unit_short_label']) != 'null') {
                $parent = Unit::where('floor_unit_number', $data[$key]['parent_unit_short_label'])->first();
                if ($parent) {
                    $data[$key]['has_sub_units'] = true;
                    $data[$key]['parent_id'] = $parent->id;
                } else {
                    $data[$key]['has_sub_units'] = false;
                    $data[$key]['parent_id'] = 0;
                }
            } else {
                $data[$key]['has_sub_units'] = false;
                $data[$key]['parent_id'] = 0;
            }

            $unitNumber = Unit::where('floor_id', $floor->id)->max('unit_number');
            if (!is_null($unitNumber)) {
                $data[$key]['unit_number'] = $unitNumber + 1;
            } else {
                $data[$key]['unit_number'] = 1;
            }

            $data[$key]['floor_unit_number'] = $data[$key]['unit_short_label'];

            $type = Type::where('slug', $data[$key]['unit_type_slug'])->first();
            $data[$key]['type_id'] = $type->id;

            $status = Status::where('name', Str::title(str_replace('-', ' ', $data[$key]['status'])))->first();
            $data[$key]['status_id'] = $status->id;

            if ($status->id == 5) {
                $data[$key]['is_for_rebate'] = true;
            } else {
                $data[$key]['is_for_rebate'] = false;
            }

            if (!is_null($data[$key]['additional_costs_name'])) {
                $facing = AdditionalCost::where('slug', $data[$key]['additional_costs_name'])->first();
                if ($facing) {
                    $data[$key]['facing_id'] = $facing->id;
                }
            }
            $data[$key]['is_imported'] = true;

            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();

            unset($data[$key]['unit_short_label']);
            unset($data[$key]['floor_short_label']);
            unset($data[$key]['unit_type_slug']);
            unset($data[$key]['status']);
            unset($data[$key]['parent_unit_short_label']);
            unset($data[$key]['additional_costs_name']);

            $unit = Unit::insert($data[$key]);
        }

        TempUnit::query()->truncate();

        return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
    }

    /**
     * Get unit details based on Unit Number (e.g 1F-01)
     */
    public function details(Request $request, $site_id, $floor_id)
    {
        $floor_id = decryptParams($floor_id);
        $site_id = decryptParams($site_id);
        $validator = \Validator::make($request->all(), [
            'unit_no' => 'required|string',
        ],
        );

        $validator->validate();

        $unit_details = Unit::where('floor_id', $floor_id)->where('floor_unit_number', $request->unit_no)->first();

        return response()->json($unit_details);

    }
}
