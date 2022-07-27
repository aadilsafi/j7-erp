<?php

namespace App\Http\Controllers;

use App\DataTables\FloorsDataTable;
use App\Services\Interfaces\FloorInterface;
use Exception;
use Illuminate\Http\Request;
use App\Http\Requests\floors\{
    storeRequest as floorStoreRequest,
    updateRequest as floorUpdateRequest,
};

class FloorController extends Controller
{
    private $floorInterface;

    public function __construct(FloorInterface $floorInterface)
    {
        $this->floorInterface = $floorInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(FloorsDataTable $dataTable, $site_id)
    {
        return $dataTable->render('app.sites.floors.index', ['site_id' => $site_id]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        if (!request()->ajax()) {
            $data = [
                'site_id' => $site_id,
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
            $floor = $this->floorInterface->getById($site_id, $id);
            if ($floor && !empty($floor)) {
                $data = [
                    'site_id' => $site_id,
                    'floor' => $floor,
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
}
