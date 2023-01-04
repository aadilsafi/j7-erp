<?php

namespace App\Http\Controllers;

use App\DataTables\ImportStakeholdersDataTable;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Services\CustomFields\CustomFieldInterface;
use Illuminate\Http\Request;
use App\DataTables\StakeholderDataTable;
use App\Http\Requests\stakeholders\{
    storeRequest as stakeholderStoreRequest,
    updateRequest as stakeholderUpdateRequest,
};
use App\Imports\StakeholdersImport;
use App\Jobs\ImportStakeholders;
use App\Models\BacklistedStakeholder;
use App\Models\City;
use App\Models\Country;
use App\Models\LeadSource;
use App\Models\Stakeholder;
use App\Models\StakeholderNextOfKin;
use App\Models\StakeholderType;
use App\Models\State;
use App\Models\TempStakeholder;
use App\Utils\Enums\StakeholderTypeEnum;
use Exception;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\HeadingRowImport;
use Redirect;
use Illuminate\Support\Facades\DB;
use Str;

class StakeholderController extends Controller
{
    private $stakeholderInterface;

    public function __construct(StakeholderInterface $stakeholderInterface, CustomFieldInterface $customFieldInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
        $this->customFieldInterface = $customFieldInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(StakeholderDataTable $dataTable, $site_id)
    {
        $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->stakeholderInterface->model()));

        $data = [
            'site_id' => $site_id,
            'customFields' => $customFields->where('in_table', true),
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

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->stakeholderInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $emtyNextOfKin[0]['id'] = 0;
            $emtyNextOfKin[0]['kin_id'] = 0;
            $emtyNextOfKin[0]['relation'] = '';

            $emtykinStakeholders[0]['id'] = 0;
            $emtykinStakeholders[0]['stakeholder_id'] = 0;
            $emtykinStakeholders[0]['relation'] = '';
            $data = [
                'site_id' => decryptParams($site_id),
                'stakeholders' => Stakeholder::all(),
                'stakeholderTypes' => StakeholderTypeEnum::array(),
                'emptyRecord' => [$this->stakeholderInterface->getEmptyInstance()],
                'customFields' => $customFields,
                'country' => Country::whereHas('cities')->whereHas('states')->get(),
                'city' => [],
                'state' => [],
                'emtyNextOfKin' => $emtyNextOfKin,
                'emtykinStakeholders' => $emtykinStakeholders,
                'contactStakeholders' => Stakeholder::where('stakeholder_as', 'i')->get(),
                'leadSources' => LeadSource::all(),
            ];
            unset($data['emptyRecord'][0]['stakeholder_types']);

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

        try {
            if (!request()->ajax()) {
                $inputs = $request->all();
                $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->stakeholderInterface->model()));
                $record = $this->stakeholderInterface->store($site_id, $inputs, $customFields);
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

        $emtykinStakeholders[0]['id'] = 0;
        $emtykinStakeholders[0]['stakeholder_id'] = 0;
        $emtykinStakeholders[0]['relation'] = '';

        try {
            $parentStakeholders = [];
            $stakeholder = $this->stakeholderInterface->getById($site_id, $id, ['contacts', 'stakeholder_types', 'nextOfKin', 'kinStakeholders']);
            $parentStakeholders = StakeholderNextOfKin::where('kin_id', $stakeholder->id)->get();
            // dd($parentStakeholders);
            $customFields = $this->customFieldInterface->getAllByModel($site_id, get_class($this->stakeholderInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields, true, $stakeholder->id);

            if ($stakeholder && !empty($stakeholder)) {
                $images = $stakeholder->getMedia('stakeholder_cnic');
                $emtyNextOfKin[0]['id'] = 0;
                $emtyNextOfKin[0]['kin_id'] = 0;
                $emtyNextOfKin[0]['relation'] = '';
                $data = [
                    'site_id' => $site_id,
                    'id' => $id,
                    'stakeholderTypes' => StakeholderTypeEnum::array(),
                    'stakeholders' => Stakeholder::where('id', '!=', $stakeholder->id)->get(),
                    'stakeholder' => $stakeholder,
                    'images' => $stakeholder->getMedia('stakeholder_cnic'),
                    'country' => Country::all(),
                    'city' => [],
                    'state' => [],
                    'emptyRecord' => [$this->stakeholderInterface->getEmptyInstance()],
                    'emtykinStakeholders' => $emtykinStakeholders,
                    'parentStakeholders' => $parentStakeholders,
                    'emtyNextOfKin' => $emtyNextOfKin,
                    'customFields' => $customFields,
                    'contactStakeholders' => Stakeholder::where('stakeholder_as', 'i')->get(),
                    'leadSources' => LeadSource::all(),
                ];
                unset($data['emptyRecord'][0]['stakeholder_types']);
                // dd($data);
                return view('app.sites.stakeholders.edit', $data);
            }

            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withDanger($ex->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(stakeholderUpdateRequest $request, $site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        try {
            if (!request()->ajax()) {
                $inputs = $request->all();

                $customFields = $this->customFieldInterface->getAllByModel($site_id, get_class($this->stakeholderInterface->model()));

                $record = $this->stakeholderInterface->update($site_id, $id, $inputs, $customFields);
                return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams($site_id)])->withDanger($ex->getMessage());
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
            $stakeholder = $this->stakeholderInterface->getById($site_id, $stakeholder_id, ['stakeholder_types', 'nextOfKin']);
            $nextOfKinId = StakeholderNextOfKin::where('stakeholder_id', $stakeholder_id)->get();
            $nextOfKin = [];
            foreach ($nextOfKinId as $key => $value) {
                $nextOfKin[] = $this->stakeholderInterface->getById($site_id, $value->kin_id, ['stakeholder_types']);
            }
            return apiSuccessResponse([$stakeholder, $nextOfKin]);
        } else {
            abort(403);
        }
    }

    public function authorizeStakeholder(Request $request, $file_name)
    {
        $file_name = decryptParams($file_name);
        $id = explode('.', $file_name);
        $id = explode('-', $id[0]);
        $stakeholder_id = $id[count($id) - 1];

        $data = [
            'file_name' => encryptParams($file_name),
            'stakeholder_id' => encryptParams($stakeholder_id),
        ];
        return view('app.sites.stakeholders.authorize', $data);
    }

    public function verifyPin(Request $request, $file_name, $stakeholder_id)
    {
        $file_name = decryptParams($file_name);
        $stakeholder_id = decryptParams($stakeholder_id);
        $stakeholder = Stakeholder::where('id', $stakeholder_id)->where('pin_code', $request->pin)->exists();
        if ($stakeholder) {
            return apiSuccessResponse($file_name);
        } else {
            return response()->json(
                [
                    'status' => false,
                    'message' => 'Pin code is not correct',
                ],
                500
            );
        }
    }
}
