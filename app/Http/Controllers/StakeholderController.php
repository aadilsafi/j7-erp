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
use App\Models\TempStakeholder;
use App\Utils\Enums\StakeholderTypeEnum;
use Exception;
use Maatwebsite\Excel\HeadingRowImport;
use Redirect;

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

            $customFields = $this->customFieldInterface->getAllByModel(decryptParams($site_id), get_class($this->stakeholderInterface->model()));
            $customFields = collect($customFields)->sortBy('order');
            $customFields = generateCustomFields($customFields);

            $data = [
                'site_id' => decryptParams($site_id),
                'stakeholders' => $this->stakeholderInterface->getAllWithTree(),
                'stakeholderTypes' => StakeholderTypeEnum::array(),
                'emptyRecord' => [$this->stakeholderInterface->getEmptyInstance()],
                'customFields' => $customFields,
            ];
            unset($data['emptyRecord'][0]['stakeholder_types']);
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
        // dd($request->all());
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
            $stakeholder = $this->stakeholderInterface->getById($site_id, $id, ['contacts', 'stakeholder_types']);

            if ($stakeholder && !empty($stakeholder)) {
                $images = $stakeholder->getMedia('stakeholder_cnic');

                $data = [
                    'site_id' => $site_id,
                    'id' => $id,
                    'stakeholderTypes' => StakeholderTypeEnum::array(),
                    'stakeholders' => $this->stakeholderInterface->getByAll($site_id),
                    'stakeholder' => $stakeholder,
                    'images' => $stakeholder->getMedia('stakeholder_cnic'),
                    'emptyRecord' => [$this->stakeholderInterface->getEmptyInstance()]
                ];
                unset($data['emptyRecord'][0]['stakeholder_types']);
                // dd($data);
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


    public function ImportPreview(Request $request, $site_id)
    {
        try {
            $model = new TempStakeholder();

            if ($request->hasfile('attachment')) {
                $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error
                TempStakeholder::query()->truncate();
                $import = new StakeholdersImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.stakeholders.storePreview', ['site_id' => $site_id]);
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

            // dd($e->failures());
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
        $model = new TempStakeholder();
        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
        } else {
            $dataTable = new ImportStakeholdersDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
            ];
            return $dataTable->with($data)->render('app.sites.floors.importFloorsPreview', $data);
        }
    }

    public function saveImport(Request $request, $site_id)
    {
        // $site_id = decryptParams($site_id);
        $model = new TempFloor();
        $tempdata = $model->all()->toArray();
        $tempCols = $model->getFillable();

        $totalFloors = Floor::max('order');
        // dd($totalFloors);
        $floors = [];
        foreach ($tempdata as $key => $items) {
            foreach ($request->fields as $k => $field) {
                if ($field == 'floor_area') {
                    $data[$key][$field] = (float)$items[$tempCols[$k]];
                } else {
                    $data[$key][$field] = $items[$tempCols[$k]];
                }
            }
            // dd($totalFloors, $totalFloors++, ++$totalFloors);

            $data[$key]['site_id'] = decryptParams($site_id);
            $data[$key]['order'] = ++$totalFloors;
            $data[$key]['active'] = true;
            $data[$key]['active'] = true;
            $data[$key]['active'] = true;
            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();
        }
        $floors = Floor::insert($data);

        if ($floors) {
            TempFloor::query()->truncate();
        }
        return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
    }
}
