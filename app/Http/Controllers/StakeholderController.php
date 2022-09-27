<?php

namespace App\Http\Controllers;

use App\Services\Stakeholder\Interface\StakeholderInterface;
use Illuminate\Http\Request;
use App\DataTables\StakeholderDataTable;
use App\Http\Requests\stakeholders\{
    storeRequest as stakeholderStoreRequest,
    updateRequest as stakeholderUpdateRequest,
};
use App\Utils\Enums\StakeholderTypeEnum;
use Exception;

class StakeholderController extends Controller
{
    private $stakeholderInterface;

    public function __construct(StakeholderInterface $stakeholderInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StakeholderDataTable $dataTable, $site_id)
    {
        //
        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.stakeholders.index', $data);
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
                'stakeholders' => $this->stakeholderInterface->getAllWithTree(),
                'stakeholderTypes' => StakeholderTypeEnum::array(),
            ];
            // dd($data);
            return view('app.sites.stakeholders.create', $data);
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
    public function store(stakeholderStoreRequest $request, $site_id)
    // public function store(Request $request, $site_id)
    {
        dd($request->all());
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                // dd($inputs);
                $record = $this->stakeholderInterface->store($site_id, $inputs);
                return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
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
        //
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);
        try {
            $stakeholder = $this->stakeholderInterface->getById($site_id, $id);

            if ($stakeholder && !empty($stakeholder)) {
                $images = $stakeholder->getMedia('stakeholder_cnic');

                $data = [
                    'site_id' => $site_id,
                    'id' => $id,
                    'stakeholderTypes' => StakeholderTypeEnum::array(),
                    'stakeholders' => $this->stakeholderInterface->getAllWithTree(),
                    'stakeholder' => $stakeholder,
                    'images' => $stakeholder->getMedia('stakeholder_cnic'),
                ];
                return view('app.sites.stakeholders.edit', $data);
            }

            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(stakeholderUpdateRequest  $request, $site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        try {
            if (!request()->ajax()) {
                $inputs = $request->all();
                $record = $this->stakeholderInterface->update($site_id, $id, $inputs);
                return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function destroySelected(Request $request, $site_id)
    {
        try {
            $site_id = decryptParams($site_id);
            if (!request()->ajax()) {
                if ($request->has('chkRole')) {

                    $record = $this->stakeholderInterface->destroy($site_id, encryptParams($request->chkRole));

                    if ($record) {
                        return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_deleted'));
                    } else {
                        return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function ajaxGetById(Request $request, $site_id, $stakeholder_id)
    {
        if ($request->ajax()) {
            $stakeholder = $this->stakeholderInterface->getById($site_id, $stakeholder_id, ['stakeholder_types']);
            return apiSuccessResponse($stakeholder);
        } else {
            abort(403);
        }
    }
}
