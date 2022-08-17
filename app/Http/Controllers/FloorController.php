<?php

namespace App\Http\Controllers;

use App\DataTables\FloorsDataTable;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\floors\{
    copyFloorRequest,
    storeRequest as floorStoreRequest,
    updateRequest as floorUpdateRequest,
};
use App\Models\Floor;
use App\Models\Site;
use App\Models\SiteConfigration;
use App\Models\Unit;
use App\Services\Interfaces\{
    FloorInterface,
    UserBatchInterface,
};
use App\Utils\Enums\{
    UserBatchActionsEnum,
    UserBatchStatusEnum,
};
use Yajra\DataTables\Facades\DataTables;

class FloorController extends Controller
{
    private $floorInterface;
    private $userBatchInterface;

    public function __construct(
        FloorInterface $floorInterface,
        UserBatchInterface $userBatchInterface,
    ) {
        $this->floorInterface = $floorInterface;
        $this->userBatchInterface = $userBatchInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FloorsDataTable $dataTable, Request $request, $site_id)
    {

        $nonActiveFloors = (new Floor())->where('active', false)->where('site_id', decryptParams($site_id))->get();
        if (!empty($nonActiveFloors) && count($nonActiveFloors) > 0) {
            return redirect()->route('sites.floors.preview', ['site_id' => encryptParams(decryptParams($site_id))]);
        }

        if ($request->ajax()) {
            $id = $request->get('id');
            $floors = (new Floor())->where('active', 1)->where('site_id', decryptParams($site_id))->get();
            return DataTables::of($floors)
                ->addIndexColumn()
                ->editColumn('check', function ($floor) {
                    return $floor;
                })
                ->editColumn('width', function ($floor) {
                    return $floor->width . '\'\'';
                })
                ->editColumn('length', function ($floor) {
                    return $floor->length . '\'\'';
                })
                ->editColumn('units_count', function ($floor) {
                    $count = $floor->units->count();
                    if (!is_null($count)) {
                        return $count > 0 ? $count : '-';
                    }
                    return '-';
                })
                ->editColumn('units_open_count', function ($floor) {
                    $count = $floor->units->where('status_id', 1)->count();
                    if (!is_null($count)) {
                        return $count > 0 ? $count : '-';
                    }
                    return '-';
                })
                ->editColumn('units_sold_count', function ($floor) {
                    $count = $floor->units->where('status_id', 5)->count();
                    if (!is_null($count)) {
                        return $count > 0 ? $count : '-';
                    }
                    return '-';
                })
                ->editColumn('units_token_count', function ($floor) {
                    $count = $floor->units->where('status_id', 2)->count();
                    if (!is_null($count)) {
                        return $count > 0 ? $count : '-';
                    }
                    return '-';
                })
                ->editColumn('units_hold_count', function ($floor) {
                    $count = $floor->units->where('status_id', 4)->count();
                    if (!is_null($count)) {
                        return $count > 0 ? $count : '-';
                    }
                    return '-';
                })
                ->editColumn('units_dp_count', function ($floor) {
                    $count = $floor->units->where('status_id', 3)->count();
                    if (!is_null($count)) {
                        return $count > 0 ? $count : '-';
                    }
                    return '-';
                })
                ->editColumn('created_at', function ($floor) {
                    return editDateColumn($floor->created_at);
                })
                ->editColumn('actions', function ($floor) {
                    return view('app.sites.floors.actions', ['site_id' => $floor->site_id, 'id' => $floor->id]);
                })
                ->rawColumns([
                    'created_at', 'units_dp_count', 'units_hold_count',
                    'units_token_count', 'units_sold_count', 'units_open_count',
                    'units_count', 'width', 'length', 'check', 'actions'
                ])
                ->make(true);
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
        $floorShortLable = (new SiteConfigration())->find(decryptParams($site_id))->floor_prefix;

        if (!request()->ajax()) {
            $data = [
                'site_id' => $site_id,
                'floorShortLable' => $floorShortLable,
            ];
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
                // dd($inputs);
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
        try {
            $floorShortLable = (new SiteConfigration())->find(decryptParams($site_id))->floor_prefix;
            $floor = $this->floorInterface->getById($site_id, $id);
            if ($floor && !empty($floor)) {
                $data = [
                    'site_id' => $site_id,
                    'floor' => $floor,
                    'floorShortLable' => $floorShortLable
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
        // return $request->input();
        if (!request()->ajax()) {

            $inputs = $request->validated();

            $record = $this->floorInterface->storeInBulk($site_id, encryptParams(auth()->user()->id), $inputs);
            // dd($record);

            // $this->userBatchInterface->store($site_id, encryptParams(auth()->user()->id), $record->id, UserBatchActionsEnum::COPY_FLOORS, UserBatchStatusEnum::PENDING);

            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess('Floor(s) will be contructed shortly!');
        } else {
            abort(403);
        }
    }

    public function preview(Request $request, $site_id)
    {
        if ($request->ajax()) {
            $id = $request->get('id');
            $units = Unit::where('floor_id', $id)->where('active', 0)->get();
            return DataTables::of($units)
                ->addIndexColumn()
                ->editColumn('type_id', function ($unit) {
                    return view(
                        'app.components.unit-preview-cell',
                        ['id' => $unit->id, 'field' => 'type_id', 'inputtype' => 'select', 'value' => $unit->type->name]
                    );
                })
                ->editColumn('status_id', function ($unit) {
                    return view(
                        'app.components.unit-preview-cell',
                        ['id' => $unit->id, 'field' => 'status_id', 'inputtype' => 'select', 'value' => $unit->status->name]
                    );
                })
                ->editColumn('name', function ($unit) {
                    return view(
                        'app.components.unit-preview-cell',
                        ['id' => $unit->id, 'field' => 'name', 'inputtype' => 'text', 'value' => $unit->name]
                    );
                })
                ->editColumn('created_at', function ($unit) {
                    return editDateColumn($unit->created_at);
                })
                ->editColumn('width', function ($unit) {
                    return view(
                        'app.components.unit-preview-cell',
                        ['id' => $unit->id, 'field' => 'width', 'inputtype' => 'number', 'value' => $unit->width]
                    );
                })
                ->editColumn('length', function ($unit) {
                    return view(
                        'app.components.unit-preview-cell',
                        ['id' => $unit->id, 'field' => 'length', 'inputtype' => 'number', 'value' => $unit->length]
                    );
                })
                ->editColumn('is_corner', function ($unit) {
                    return view(
                        'app.components.checkbox',
                        ['id' => $unit->id, 'data' => 'null', 'field' => 'is_corner', 'is_true' => $unit->is_corner]
                    );
                })
                ->editColumn('is_facing', function ($unit) {
                    return view(
                        'app.components.checkbox',
                        ['id' => $unit->id, 'data' => $unit, 'field' => 'is_facing', 'is_true' => $unit->is_facing]
                    );
                })
                ->editColumn('net_area', function ($unit) {
                    return view(
                        'app.components.unit-preview-cell',
                        ['id' => $unit->id, 'field' => 'net_area', 'inputtype' => 'number', 'value' => $unit->net_area]
                    );
                })
                ->editColumn('gross_area', function ($unit) {
                    return view(
                        'app.components.unit-preview-cell',
                        ['id' => $unit->id, 'field' => 'gross_area', 'inputtype' => 'number', 'value' => $unit->gross_area]
                    );
                })
                ->editColumn('price_sqft', function ($unit) {
                    return view(
                        'app.components.unit-preview-cell',
                        ['id' => $unit->id, 'field' => 'price_sqft', 'inputtype' => 'number', 'value' => $unit->price_sqft]
                    );
                })
                ->rawColumns([
                    'name', 'created_at', 'width', 'length', 'is_corner', 'is_facing', 'type_id',
                    'status_id', 'net_area', 'gross_area', 'price_sqft'
                ])
                ->make(true);
        }
        $floors = (new Floor())->where('site_id', decryptParams($site_id))->where('active', 0)->select('id')->get();
        return view(
            'app.sites.floors.preview',
            ['site_id' => encryptParams(decryptParams($site_id)), 'floors' => $floors]
        );
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
}
