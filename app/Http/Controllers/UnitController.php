<?php

namespace App\Http\Controllers;

use App\DataTables\UnitsDataTable;
use App\DataTables\UnitsPreviewDataTable;
use App\Models\{Facing, Floor, Site, Status, Unit};
use App\Services\Interfaces\{AdditionalCostInterface, UnitInterface, UnitTypeInterface, UserBatchInterface};
use Illuminate\Http\Request;
use App\Http\Requests\units\{
    storeRequest as unitStoreRequest,
    updateRequest as unitUpdateRequest
};
use App\Utils\Enums\UserBatchActionsEnum;
use App\Utils\Enums\UserBatchStatusEnum;
use Exception;

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
                'siteConfiguration' => getSiteConfiguration(decryptParams($site_id)),
                'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
                'types' => $this->unitTypeInterface->getAllWithTree(),
                'statuses' => (new Status())->all(),
            ];

            return view('app.sites.floors.units.create', $data);
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
                // dd($inputs);
                if ($inputs['add_bulk_unit']) {
                    $record = $this->unitInterface->storeInBulk($site_id, $floor_id, $inputs);

                    $this->userBatchInterface->store($site_id, encryptParams(auth()->user()->id), $record->id, UserBatchActionsEnum::COPY_UNITS, UserBatchStatusEnum::PENDING);

                    return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id,])->withSuccess('Unit(s) will be contructed shortly!');
                } else {
                    $record = $this->unitInterface->store($site_id, $floor_id, $inputs);
                }

                return redirect()->route('sites.floors.units.index', ['site_id' => $site_id, 'floor_id' => $floor_id,])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
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
            $unit = $this->unitInterface->getById($site_id, $floor_id, $id);
            if ($unit && !empty($unit)) {
                $data = [
                    'site' => (new Site())->find(decryptParams($site_id)),
                    'floor' => (new Floor())->find(decryptParams($floor_id)),
                    'siteConfiguration' => getSiteConfiguration(decryptParams($site_id)),
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
        // $data = [
        //     'site' => (new Site())->find(decryptParams($site_id)),
        //     'floor' => (new Floor())->find(decryptParams($floor_id))
        // ];
        $data = [
            'site' => (new Site())->find(1),
            'floor' => (new Floor())->find(2),
            'types' => $this->unitTypeInterface->getAllWithTree(),
        ];

        return $dataTable->with($data)->render('app.sites.floors.units.preview', $data);
    }

    public function updateUnitName(Request $request)
    {
        try {
            $unit = Unit::find((int)$request->get('id'));
            $value = $request->get('value');
            $field = $request->get('field');
            $facing = '';
            if ($field == 'is_corner') {
                $unit->is_corner = !($unit->is_corner);
            } elseif ($field == 'is_facing') {
                $unit->is_facing = !($unit->is_facing);
                if($unit->is_facing){
                    if($unit->facing_id == null){
                        $unit->facing_id = 1;
                    }
                    $facing = 'yes';
                }
                else{
                    $facing = 'no';
                }
            } else {
                $unit->{$field} = $value;
            }
            $unit->save();
            return response()->json([
                'message' => "Updated Successfully",
                'data' => [
                    'facing' => $facing
                ]
            ]);
        } catch (Exception $ex) {
            return response()->json([
                'message' => $ex->getMessage(),
            ]);
        }
    }

    public function getUnitInput(Request $request)
    {
        try {

            sleep(1);

            $field = $request->get('field');
            if($field == 'status_id'){
                $unit = Unit::find((int)$request->get('id'));
                $statuses = (new Status())->all();
                $response = view('app.components.select-dropdown',
                    ['id'=>$request->get('id'),'field'=>$field,'data_id'=>$unit->status->id,
                    'type'=>'status','value'=>$unit->status->name, 'values'=>$statuses])->render();
            }
            else if($field == 'type_id'){

                $unit = Unit::find((int)$request->get('id'));
                $types = $this->unitTypeInterface->getAllWithTree();
                $response = view('app.components.select-dropdown',
                    ['id'=>$request->get('id'),'field'=>$field,'data_id'=>$unit->type->id,
                    'type'=>'type','value'=>$unit->type->name, 'values'=>$types])->render();
            }
            else if($field == 'facing_id'){

                $unit = Unit::find((int)$request->get('id'));
                $additionalCosts = $this->additionalCostInterface->getAllWithTree(encryptParams($unit->floor->site->id));
                $response = view('app.components.select-dropdown',
                    ['id'=>$request->get('id'),'field'=>$field,'data_id'=>$unit->facing->id,
                    'type'=>'facing','value'=>$unit->facing->name, 'values'=>$additionalCosts])->render();
            }
            else{

                $response = view('app.components.text-number-field', [
                    'field' => $field,
                    'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                    'value' => $request->get('value')
                ])->render();
            }

            return apiSuccessResponse($response);
        } catch (Exception $ex) {
            return apiErrorResponse($ex->getMessage());
        }
    }

    public function drawFacingField(Request $request)
    {
        try {
                $unit = Unit::find((int)$request->get('id'));
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
}
