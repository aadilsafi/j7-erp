<?php

namespace App\Http\Controllers;

use App\DataTables\FloorsDataTable;
use App\DataTables\FloorsPreviewDataTable;
use App\DataTables\ImportFloorsDataTable;
use App\Exceptions\GeneralException;
use App\Http\Requests\FileBuyBack\store;
use App\Services\CustomFields\CustomFieldInterface;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\floors\{
    copyFloorRequest,
    storeRequest as floorStoreRequest,
    updateRequest as floorUpdateRequest,
};
use App\Imports\FloorImport;
use App\Imports\FloorImportSimple;
use App\Models\{
    Floor,
    Unit,
    Site,
    TempFloor,
};
use App\Services\Interfaces\{
    FloorInterface,
    UserBatchInterface,
};
use App\Utils\Enums\{
    UserBatchActionsEnum,
    UserBatchStatusEnum,
};
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\HeadingRowImport;
use PDO;
use Yajra\DataTables\Facades\DataTables;
use Redirect;

class FloorController extends Controller
{
    private $floorInterface;
    private $userBatchInterface;

    public function __construct(
        FloorInterface $floorInterface,
        UserBatchInterface $userBatchInterface,
        CustomFieldInterface $customFieldInterface
    ) {
        $this->floorInterface = $floorInterface;
        $this->userBatchInterface = $userBatchInterface;
        $this->customFieldInterface = $customFieldInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FloorsDataTable $dataTable, $site_id, Request $request)
    {
        $nonActiveFloors = (new Floor())->where('active', false)->where('site_id', decryptParams($site_id))->get();
        if (!empty($nonActiveFloors) && count($nonActiveFloors) > 0) {
            return redirect()->route('sites.floors.preview', ['site_id' => encryptParams(decryptParams($site_id))]);
        }

        if ($request->ajax()) {
            return $dataTable->with(['site_id' => decryptParams($site_id)])->ajax();
        }

        $totalFloors = Floor::count();
        
        return view('app.sites.floors.index', ['site_id' => encryptParams(decryptParams($site_id)), 'totalFloors' => $totalFloors]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        $site = (new Site())->where('id', decryptParams($site_id))->with('siteConfiguration')->first();

        $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->floorInterface->model()));
        $customFields = collect($customFields)->sortBy('order');
        $customFields = generateCustomFields($customFields);

        if (!request()->ajax()) {
            $data = [
                'site_id' => $site->id,
                'floorShortLable' => $site->siteConfiguration->floor_prefix,
                'floorOrder' => getMaxFloorOrder(decryptParams($site_id)) + 1,
                'customFields' => $customFields
            ];

            // dd($data);
            return view('app.sites.floors.create', $data);
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
    public function store(floorStoreRequest $request, $site_id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->floorInterface->store($site_id, $inputs);

                return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
        $site = (new Site())->where('id', decryptParams($site_id))->with('siteConfiguration')->first();
        try {
            $floor = $this->floorInterface->getById($site_id, $id);
            if ($floor && !empty($floor)) {
                $data = [
                    'site_id' => $site->id,
                    'floor' => $floor,
                    'floorShortLable' => $site->siteConfiguration->floor_prefix,
                ];

                // dd($data);

                return view('app.sites.floors.edit', $data);
            }

            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(floorUpdateRequest $request, $site_id, $id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                // return [$site_id, $id, $inputs];
                $record = $this->floorInterface->update($site_id, $id, $inputs);
                return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function destroySelected(Request $request, $site_id)
    {
        try {
            if (!request()->ajax()) {
                if ($request->has('chkTableRow')) {

                    $record = $this->floorInterface->destroy($site_id, encryptParams($request->chkTableRow));

                    if ($record) {
                        return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_deleted'));
                    } else {
                        return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function copyView(Request $request, $site_id)
    {
        if (!request()->ajax()) {
            $data = [
                'site_id' => encryptParams(decryptParams($site_id)),
                'floors' => (new Floor())->whereSiteId(decryptParams($site_id))->withCount('units')->get(),
            ];

            return view('app.sites.floors.copy', $data);
        } else {
            abort(403);
        }
    }

    public function copyStore(copyFloorRequest $request, $site_id)
    {
        // dd($request->all());
        // return $request->input();
        if (!request()->ajax()) {

            $inputs = $request->validated();

            $record = $this->floorInterface->storeInBulk(decryptParams($site_id), auth()->user()->id, $inputs);

            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess('Floor(s) will be contructed shortly!');
        } else {
            abort(403);
        }
    }

    public function preview(Request $request, FloorsPreviewDataTable $dataTable, $site_id)
    {
        if ($request->ajax()) {
            $data['floor_id'] = $request->get('id');

            return $dataTable->with($data)->ajax();
        }
        $site_id = decryptParams($site_id);
        $floors = (new Floor())->where('site_id', $site_id)->where('active', 0)->get();
        return view('app.sites.floors.preview', ['site_id' => $site_id, 'floors' => $floors]);
    }

    public function saveChanges(Request $request, $site_id)
    {

        try {

            $floors = (new Floor())->where('site_id', decryptParams($site_id))->where('active', 0)->get()->pluck('id')->toArray();
            (new Floor())->where('site_id', decryptParams($site_id))->where('active', false)->update([
                'active' => true
            ]);
            (new Unit())->whereIn('floor_id', $floors)->update([
                'active' => true
            ]);

            return redirect()->route('sites.floors.index', ['site_id' => encryptParams(decryptParams($site_id))]);
        } catch (Exception $th) {
            return redirect()->route('dashboard')->withDanger($th->getMessage());
        }
    }

    public function getPendingFloors(Request $request, $site_id)
    {
        if ($request->ajax()) {
            $floors = (new Floor())->where('site_id', decryptParams($site_id))->where('active', 0)->select(['id', 'site_id'])->get();
            return response()->json($floors);
        }
    }

    public function getUnitInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempFloor = (new TempFloor())->find((int)$request->get('id'));

            switch ($field) {
                case 'name':
                    if ($request->get('updateValue') == 'true') {
                        $tempFloor->name = $request->get('value');

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

                case 'floor_area':
                    if ($request->get('updateValue') == 'true') {
                        $tempFloor->floor_area = $request->get('value');

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

                case 'short_label':
                    if ($request->get('updateValue') == 'true') {
                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|unique:floors,short_label',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $validator2 = \Validator::make($request->all(), [
                            'value' => [
                                Rule::unique('temp_floors', 'short_label')->ignore($request->get('id'))
                            ],
                        ]);

                        if ($validator2->fails()) {
                            return apiErrorResponse($validator2->errors()->first('value'));
                        }
                        $tempFloor->short_label = $request->get('value');

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
            $tempFloor->save();
            return apiSuccessResponse($response);
        } catch (Exception $ex) {
            return apiErrorResponse($ex->getMessage());
        }
    }

    public function ImportPreview(Request $request, $site_id)
    {
        try {
            $model = new TempFloor();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx'
                ]);
                $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error
                TempFloor::query()->truncate();
                $import = new FloorImportSimple($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.floors.storePreview', ['site_id' => $site_id]);
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
        $model = new TempFloor();
        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
        } else {
            $required = [
                'name',
                'floor_area',
                'short_label'
            ];

            $dataTable = new ImportFloorsDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
                'required_fields' => $required,

            ];
            return $dataTable->with($data)->render('app.sites.floors.importFloorsPreview', $data);
        }
    }

    public function saveImport(Request $request, $site_id)
    {
        $validator = \Validator::make($request->all(), [
            'fields.*' => 'required|distinct',
        ], [
            'fields.*.required' => 'Must Select all Fields',
            'fields.*.distinct' => 'Field can not be duplicated',
        ]);

        $validator->validate();

        $model = new TempFloor();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();

        // dd($tempdata);
        $totalFloors = Floor::max('order');

        $data = [];
        foreach ($tempdata as $key => $items) {
            foreach ($tempCols as $k => $field) {
                if ($field == 'floor_area') {
                    $data[$key][$field] = (float)$items[$tempCols[$k]];
                } else {
                    $data[$key][$field] = $items[$tempCols[$k]];
                }
            }

            $data[$key]['site_id'] = decryptParams($site_id);
            $data[$key]['order'] = ++$totalFloors;
            $data[$key]['active'] = true;
            $data[$key]['is_imported'] = true;

            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();
        }

        // dd($data);
        $floors = Floor::insert($data);

        if ($floors) {
            TempFloor::query()->truncate();
        }
        return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
    }

    public function floorPlan(Request $request, $site_id, $id)
    {
        $id = decryptParams($id);
        $site_id = decryptParams($site_id);

        $floor = Floor::find($id);
        $floor_plan = $floor->floor_plan;

        return view('app.sites.floors.floor-plan', [
            'site_id' => $site_id,
            'id' => $id,
            'floor_plan' => $floor_plan,
        ]);
    }

    public function floorPlanUpload(Request $request, $site_id, $id)
    {
        $id = decryptParams($id);
        $site_id = decryptParams($site_id);
        $validator = \Validator::make(
            $request->all(),
            [
                'floorplan_file' => 'required|file',
            ],
        );

        $validator->validate();

        $json = file_get_contents($request->file('floorplan_file'));

        if(!$json){
        return back()->with('error', 'Invalid file format');
        }

        $floor = (new Floor())->find($id);
        $floor->floor_plan = $json;
        $floor->saveOrFail();
        
        return back()->with('success', 'Floorplan uploaded successfully');

    }
}
