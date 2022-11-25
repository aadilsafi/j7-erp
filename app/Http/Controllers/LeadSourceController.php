<?php

namespace App\Http\Controllers;

use App\DataTables\LeadSourceDataTable;
use App\Services\LeadSource\LeadSourceInterface;
use App\Services\CustomFields\CustomFieldInterface;

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

    public function __construct(LeadSourceInterface $leadSourceInterface, CustomFieldInterface $customFieldInterface)
    {
        $this->leadSourceInterface = $leadSourceInterface;
        $this->customFieldInterface = $customFieldInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(LeadSourceDataTable $dataTable, $site_id)
    {
        $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->leadSourceInterface->model()));

        $data = [
            'site_id' => decryptParams($site_id),
            'customFields' => $customFields->where('in_table', true),
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

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->leadSourceInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site_id' => decryptParams($site_id),
                'customFields' => $customFields
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

                $inputs = $request->all();
                $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->leadSourceInterface->model()));

                $site_id = decryptParams($site_id);

                $record = $this->leadSourceInterface->store($site_id, $inputs, $customFields);
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
            $customFields = $this->customFieldInterface->getAllByModel($site_id, get_class($this->leadSourceInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields, true, $leadSource->id);

            if ($leadSource && !empty($leadSource)) {

                $data = [
                    'site_id' => $site_id,
                    'leadSource' => $leadSource,
                    'customFields' => $customFields
                ];
                // dd($data);
                return view('app.sites.lead-sources.edit', $data);
            }

            return redirect()->route('sites.lead-sources.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
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
                $inputs = $request->all();

                $site_id = decryptParams($site_id);
                $id = decryptParams($id);
                $customFields = $this->customFieldInterface->getAllByModel($site_id, get_class($this->leadSourceInterface->model()));

                $record = $this->leadSourceInterface->update($site_id, $id, $inputs, $customFields);

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
