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
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Models\TempStakeholder;
use App\Utils\Enums\StakeholderTypeEnum;
use Exception;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\HeadingRowImport;
use Redirect;
use Illuminate\Support\Facades\DB;

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

    public function getUnitInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempStakeholder = (new TempStakeholder())->find((int)$request->get('id'));

            $validator = \Validator::make($request->all(), [
                'value' => 'required',
            ]);

            if ($validator->fails()) {
                return apiErrorResponse($validator->errors()->first('value'));
            }

            switch ($field) {
                case 'full_name':
                    if ($request->get('updateValue') == 'true') {
                        $tempStakeholder->full_name = $request->get('value');
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

                case 'father_name':
                    if ($request->get('updateValue') == 'true') {
                        $tempStakeholder->father_name = $request->get('value');

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

                case 'cnic':
                    if ($request->get('updateValue') == 'true') {
                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|unique:stakeholders,cnic',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $validator2 = \Validator::make($request->all(), [
                            'value' => [
                                Rule::unique('stakeholders', 'cnic')->ignore($request->get('id'))
                            ],
                        ]);

                        if ($validator2->fails()) {
                            return apiErrorResponse($validator2->errors()->first('value'));
                        }
                        $tempStakeholder->cnic = $request->get('value');

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

                case 'ntn':
                    if ($request->get('updateValue') == 'true') {
                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|unique:stakeholders,ntn',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $validator2 = \Validator::make($request->all(), [
                            'value' => [
                                Rule::unique('temp_stakeholders', 'ntn')->ignore($request->get('id'))
                            ],
                        ]);

                        if ($validator2->fails()) {
                            return apiErrorResponse($validator2->errors()->first('value'));
                        }
                        $tempStakeholder->ntn = $request->get('value');

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

                case 'contact':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->contact = $request->get('value');

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

                case 'address':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->address = $request->get('value');

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

                case 'comments':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->comments = $request->get('value');

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

                case 'parent_cnic':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|exists:App\Models\TempStakeholder,cnic',
                        ], [
                            'value.exists' => 'This Value does not Exists.'
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempStakeholder->parent_cnic = $request->get('value');

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
                case 'relation':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->relation = $request->get('value');

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
                case 'is_dealer':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->is_dealer =  !$tempStakeholder->is_dealer;

                        $response =  view('app.components.checkbox', [
                            'id' => $request->get('id'),
                            'data' => $tempStakeholder,
                            'field' => $field,
                            'is_true' => $tempStakeholder->is_vendor,
                            'value' => $tempStakeholder->is_vendor
                        ])->render();
                    }

                    break;

                case 'is_customer':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->is_customer = !$tempStakeholder->is_customer;

                        $response =  view('app.components.checkbox', [
                            'id' => $request->get('id'),
                            'data' => $tempStakeholder,
                            'field' => $field,
                            'is_true' => $tempStakeholder->is_vendor,
                            'value' => $tempStakeholder->is_vendor
                        ])->render();
                    }

                    break;
                case 'is_kin':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->is_kin = !$tempStakeholder->is_kin;

                        $response =  view('app.components.checkbox', [
                            'id' => $request->get('id'),
                            'data' => $tempStakeholder,
                            'field' => $field,
                            'is_true' => $tempStakeholder->is_vendor,
                            'value' => $tempStakeholder->is_vendor
                        ])->render();
                    }

                    break;
                case 'is_vendor':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->is_vendor = !$tempStakeholder->is_vendor;

                        $response =  view('app.components.checkbox', [
                            'id' => $request->get('id'),
                            'data' => $tempStakeholder,
                            'field' => $field,
                            'is_true' => $tempStakeholder->is_vendor,
                            'value' => $tempStakeholder->is_vendor
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
            $tempStakeholder->save();
            return apiSuccessResponse($response);
        } catch (Exception $ex) {
            return apiErrorResponse($ex->getMessage());
        }
    }

    public function ImportPreview(Request $request, $site_id)
    {
        try {
            $model = new TempStakeholder();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment'=> 'required|mimes:xlsx'
                 ]);
                $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error
                TempStakeholder::query()->truncate();
                $import = new StakeholdersImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.stakeholders.storePreview', ['site_id' => $site_id]);
            }else{
                return Redirect::back()->withDanger('Select File to Import');

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
            return $dataTable->with($data)->render('app.sites.stakeholders.importFloorsPreview', $data);
        }
    }

    public function saveImport(Request $request, $site_id)
    {
        // DB::transaction(function () use ($request, $site_id) {

            $model = new TempStakeholder();
            $tempdata = $model->all()->toArray();
            $tempCols = $model->getFillable();

            $stakeholder = [];
            foreach ($tempdata as $key => $items) {
                foreach ($tempCols as $k => $field) {
                    $data[$key][$field] = $items[$tempCols[$k]];
                }
                $data[$key]['site_id'] = decryptParams($site_id);

                if ($data[$key]['parent_cnic'] != null) {
                    $parent = Stakeholder::where('cnic', $data[$key]['parent_cnic'])->first();
                    $data[$key]['parent_id'] = $parent->id;
                } else {
                    $data[$key]['parent_id'] = 0;
                }

                unset($data[$key]['parent_cnic']);
                unset($data[$key]['is_dealer']);
                unset($data[$key]['is_vendor']);
                unset($data[$key]['is_kin']);
                unset($data[$key]['is_customer']);

                $stakeholder = Stakeholder::create($data[$key]);

                $stakeholdertype = [
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => 'C',
                        'stakeholder_code' => 'C-00' . $stakeholder->id,
                        'status' => $items['is_customer'] ? 1 : 0,
                        'created_at'=> now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => 'V',
                        'stakeholder_code' => 'V-00' . $stakeholder->id,
                        'status' => $items['is_vendor'] ? 1 : 0,
                        'created_at'=> now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => 'D',
                        'stakeholder_code' => 'D-00' . $stakeholder->id,
                        'status' => $items['is_dealer'] ? 1 : 0,
                        'created_at'=> now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => 'K',
                        'stakeholder_code' => 'K-00' . $stakeholder->id,
                        'status' => $items['is_kin'] ? 1 : 0,
                        'created_at'=> now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => 'L',
                        'stakeholder_code' => 'L-00' . $stakeholder->id,
                        'status' => 0,
                        'created_at'=> now(),
                        'updated_at' => now(),
                    ]
                ];

                $stakeholder_type = StakeholderType::insert($stakeholdertype);
            }

            TempStakeholder::query()->truncate();
            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
        // });
    }
}
