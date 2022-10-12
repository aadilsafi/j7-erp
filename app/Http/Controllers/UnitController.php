<?php

namespace App\Http\Controllers;

use App\DataTables\{UnitsDataTable, UnitsPreviewDataTable};
use App\Models\{Floor, Site, Status, Unit};
use App\Services\Interfaces\{UnitInterface, UnitTypeInterface, UserBatchInterface};
use Illuminate\Http\Request;
use App\Http\Requests\units\{
    storeRequest as unitStoreRequest,
    updateRequest as unitUpdateRequest
};
use App\Services\AdditionalCosts\AdditionalCostInterface;
use App\Utils\Enums\{UserBatchActionsEnum, UserBatchStatusEnum};
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
    ) {
        $this->unitInterface = $unitInterface;
        $this->additionalCostInterface = $additionalCostInterface;
        $this->unitTypeInterface = $unitTypeInterface;
        $this->userBatchInterface = $userBatchInterface;
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
            $data = [
                'site' => (new Site())->find(decryptParams($site_id)),
                'floor' => (new Floor())->find(decryptParams($floor_id)),
                'siteConfiguration' => getSiteConfiguration($site_id),
                'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                'types' => $this->unitTypeInterface->getAllWithTree(),
                'statuses' => (new Status())->all(),
                'max_unit_number' => getMaxUnitNumber(decryptParams($floor_id)) + 1,
            ];

            return view('app.sites.floors.units.create', $data);
        } else {
            abort(403);
        }
    }

    function fabUnits($site_id, $floor_id)
    {

        if (!request()->ajax()) {
            $data = [
                'site' => (new Site())->find(decryptParams($site_id)),
                'floor' => (new Floor())->find(decryptParams($floor_id)),
                'siteConfiguration' => getSiteConfiguration($site_id),
                'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                'units' => (new Unit())->where('status_id',1)->with('status', 'type')->get(),
                'types' => $this->unitTypeInterface->getAllWithTree(),
                'statuses' => (new Status())->all(),
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
                    'types' => $this->unitTypeInterface->getAllWithTree(),
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
            'types' => $this->unitTypeInterface->getAllWithTree(),
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
                    $types = $this->unitTypeInterface->getAllWithTree();
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


    public function getUnitData(Request $request, $site_id, $floor_id){
 
        $unit = (new Unit())->find($request->unit_id);
        
        return response()->json([
            'success' => true,
            'unit' => $unit,
            'max_unit_number' => getMaxUnitNumber($unit->floor_id) + 1,
            'floor_name' => $unit->floor->name
        ], 200);
    }
}
