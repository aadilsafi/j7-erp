<?php

namespace App\Http\Controllers;

use App\DataTables\LeadSourceDataTable;
use App\Services\LeadSource\LeadSourceInterface;
use Illuminate\Http\Request;
use App\Http\Requests\leadSources\{
    storeRequest as leadSourcesStoreRequest,
    updateRequest as leadSourcesUpdateRequest
};
use App\Models\Site;
use Exception;

class LeadSourceController extends Controller
{

    private $leadSourceInterface;

    public function __construct(LeadSourceInterface $leadSourceInterface)
    {
        $this->leadSourceInterface = $leadSourceInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LeadSourceDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => decryptParams($site_id)
        ];

        return $dataTable->with($data)->render('app.sites.lead-sources.index', $data);
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
                'site_id' => decryptParams($site_id),
            ];

            return view('app.sites.lead-sources.create', $data);
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
    public function store(leadSourcesStoreRequest $request, $site_id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();

                $site_id = decryptParams($site_id);

                $record = $this->leadSourceInterface->store($site_id, $inputs);
                return redirect()->route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong'));
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
            $site_id = decryptParams($site_id);
            $id = decryptParams($id);

            $leadSource = $this->leadSourceInterface->getById($site_id, $id);

            if ($leadSource && !empty($leadSource)) {

                $data = [
                    'site_id' => $site_id,
                    'leadSource' => $leadSource,
                ];
                // dd($data);
                return view('app.sites.lead-sources.edit', $data);
            }

            return redirect()->route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            dd($ex->getMessage());
            return redirect()->route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(leadSourcesUpdateRequest $request, $site_id, $id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();

                $site_id = decryptParams($site_id);
                $id = decryptParams($id);

                $record = $this->leadSourceInterface->update($site_id, $id, $inputs,);

                return redirect()->route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong'));
        }
    }

    public function destroySelected(Request $request, $site_id)
    {
        try {

            $site_id = decryptParams($site_id);

            if (!request()->ajax()) {
                if ($request->has('chkRole')) {

                    $record = $this->leadSourceInterface->destroy($site_id, $request->chkRole);

                    return redirect()->route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_deleted'));
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong'));
        }
    }
}
