<?php

namespace App\Http\Controllers;

use App\DataTables\FloorsDataTable;
use App\DataTables\FloorsPreviewDataTable;
use App\DataTables\ImportFloorsDataTable;
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
use Illuminate\Support\Collection;
use Yajra\DataTables\Facades\DataTables;
use Maatwebsite\Excel\Facades\Excel;

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

        return view('app.sites.floors.index', ['site_id' => encryptParams(decryptParams($site_id))]);
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
        return view('app.sites.floors.preview',['site_id' => $site_id, 'floors' => $floors]);
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

    public function storePreview(ImportFloorsDataTable $dataTable, $site_id)
    {

        // $path = $request->file_path;

        // TempFloor::query()->truncate();
        // Excel::import(new FloorImport(request()->get('fields')), $path,'public');
        // // Storage::delete($path);
        $model = new TempFloor();

        $data = [
            'site_id' => decryptParams($site_id),
            'final_preview' => true,
            'preview' => false,
            'db_fields' =>  $model->getFillable(),
        ];


        return $dataTable->with($data)->render('app.sites.floors.importFloorsPreview', $data);
    }

    public function ImportPreview(ImportFloorsDataTable $dataTable, Request $request, $site_id)
    {
        $model = new TempFloor();
        TempFloor::query()->truncate();

        Excel::Import(new FloorImport($model->getFillable()), $request->file('attachment'));

        return redirect()->route('sites.floors.storePreview', ['site_id' => encryptParams($site_id)]);
        // $data = [
        //     'site_id' => decryptParams($site_id),
        //     'preview' => true,
        //     'db_fields' =>  $model->getFillable(),
        // ];
        // return $dataTable->with($data)->render('app.sites.floors.importFloorsPreview', $data);

        // $data = Excel::import(new FloorImport, $request->file('attachment'));
        // return redirect()->back()->with('success', 'All good!');
        // TempFloor::query()->truncate();
        // TempFloor::truncate();
        // dd($request->all());
        // $import = new FloorImport();
        // $import->import($request->file('attachment'));

        // dd($import->errors());
        // $data = Excel::import(new FloorImport, $request->file('attachment'));

        //     if ($request->ajax()) {
        //         $array = Excel::toArray(new FloorImport, $request->file('attachment'));

        //        $importData = new Collection($array[0]);
        //        return Datatables::of($importData)->make(true);
        //    }


        // $reader = new Xlsx();

        // $spreadsheet = $reader->load($file->getrealPath());

        // $sheet = $spreadsheet->getActiveSheet();
        // $maxCell = $sheet->getHighestRowAndColumn();
        // $floorData = $sheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);

        // $headerRow = $floorData[0];

        // $importFloorData = [];
        // foreach ($floorData as $key => $fd) {
        //     if ($key > 0) {
        //         foreach ($fd as $k => $row) {
        //             $importFloorData[$headerRow[$k]][] = $row;
        //         }
        //     }
        // }



    }
    public function test()
    {
        // dd($data);
        // dd($request->all());
        // $file = $request->file('attachment');


        // $name_gen = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
        // $file = $file->move("imports/", $name_gen);


        // $reader = new Xlsx();

        // $spreadsheet = $reader->load($file->getrealPath());

        // $sheet = $spreadsheet->getActiveSheet();
        // $maxCell = $sheet->getHighestRowAndColumn();
        // $floorData = $sheet->rangeToArray('A1:' . $maxCell['column'] . $maxCell['row']);

        // $headerRow = $floorData[0];

        // $importFloorData = [];
        // foreach ($floorData as $key => $fd) {
        //     if ($key > 0) {
        //         foreach ($fd as $k => $row) {
        //             $importFloorData[$headerRow[$k]][] = $row;
        //         }
        //     }
        // }


        // $data = [
        //     'site_id' => decryptParams($site_id),
        //     'floors' => $importFloorData,
        //     'preview' => true
        // ];
        // return view('app.sites.floors.importFloors', $data);
    }
}
