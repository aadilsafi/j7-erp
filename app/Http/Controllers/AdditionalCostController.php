<?php

namespace App\Http\Controllers;

use App\DataTables\AdditionalCostsDataTable;
use Illuminate\Http\Request;
use App\Http\Requests\additionalCosts\{
    storeRequest as additionalCostStoreRequest,
    updateRequest as additionalCostUpdateRequest
};
use App\Services\AdditionalCosts\AdditionalCostInterface;
use Exception;

class AdditionalCostController extends Controller
{
    private $additionalCostInterface;

    public function __construct(AdditionalCostInterface $additionalCostInterface)
    {
        $this->additionalCostInterface = $additionalCostInterface;
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
            $data = [
                'site_id' => $site_id,
                'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
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
                $inputs = $request->validated();
                $record = $this->additionalCostInterface->store($site_id, $inputs);
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
                $data = [
                    'site_id' => $site_id,
                    'additionalCost' => $additionalCost,
                    'additionalCosts' => $this->additionalCostInterface->getAllWithTree($site_id),
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
                $inputs = $request->validated();
                // return [$site_id, $id, $inputs];
                $record = $this->additionalCostInterface->update($site_id, $inputs, $id);
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
}
