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
use App\Models\BacklistedStakeholder;
use App\Models\City;
use App\Models\Country;
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

            $emtyNextOfKin[0]['id'] = 0;
            $emtyNextOfKin[0]['kin_id'] = 0;
            $emtyNextOfKin[0]['relation'] = '';
           
            $data = [
                'site_id' => decryptParams($site_id),
                'stakeholders' => $this->stakeholderInterface->getAllWithTree(),
                'stakeholderTypes' => StakeholderTypeEnum::array(),
                'emptyRecord' => [$this->stakeholderInterface->getEmptyInstance()],
                'customFields' => $customFields,
                'country' => Country::all(),
                'city' => City::all(),
                'state' => State::all(),
                'emtyNextOfKin' => $emtyNextOfKin,
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
                $inputs = $request->validated();
                // dd($inputs);
                $blackListedData = BacklistedStakeholder::where('cnic', $inputs['cnic'])->first();
                //
                if (isset($blackListedData)) {
                    return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger('Stakeholder is blacklisted');
                }
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
            $stakeholder = $this->stakeholderInterface->getById($site_id, $id, ['contacts', 'stakeholder_types', 'nextOfKin']);

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
                    'city' => City::all(),
                    'state' => State::all(),
                    'emptyRecord' => [$this->stakeholderInterface->getEmptyInstance()],
                    'emtyNextOfKin' => $emtyNextOfKin,
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
    public function update(stakeholderUpdateRequest $request, $site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        try {
            if (!request()->ajax()) {
                $inputs = $request->all();
                $blackListedData = BacklistedStakeholder::where('cnic', $inputs['cnic'])->first();
                if (isset($blackListedData)) {
                    return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger('Stakeholder is blacklisted');
                }
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

    public function getInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempStakeholder = (new TempStakeholder())->find((int)$request->get('id'));

            // $validator = \Validator::make($request->all(), [
            //     'value' => 'required',
            // ]);

            // if ($validator->fails()) {
            //     return apiErrorResponse($validator->errors()->first('value'));
            // }

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
                                Rule::unique('temp_stakeholders', 'cnic')->ignore($request->get('id'))
                            ],
                        ]);

                        if ($validator2->fails()) {
                            return apiErrorResponse($validator2->errors()->first('value'));
                        }
                        $blacklisted = BacklistedStakeholder::where('cnic', $request->get('value'))
                            ->first();
                        if ($blacklisted) {
                            return apiErrorResponse('This CNIC is Blacklisted.');
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
                            'value' => 'sometimes|unique:stakeholders,ntn',
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

                        $tempStakeholder->parent_cnic = json_encode($request->get('value'));

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
                case 'optional_contact_number':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->optional_contact_number = json_encode($request->get('value'));

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

                        $tempStakeholder->relation = json_encode($request->get('value'));

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
                        $tempStakeholder->save();

                        $values = ['FALSE' => 'No', 'TRUE' => 'Yes'];
                        $response =  view(
                            'app.components.input-select-fields',
                            [
                                'id' => $request->get('id'),
                                'field' => $field,
                                'values' => $values,
                                'selectedValue' => $tempStakeholder->is_dealer
                            ]
                        )->render();
                    }

                    break;

                case 'is_customer':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->is_customer = !$tempStakeholder->is_customer;
                        $tempStakeholder->save();

                        $values = ['FALSE' => 'No', 'TRUE' => 'Yes'];
                        $response =  view(
                            'app.components.input-select-fields',
                            [
                                'id' => $request->get('id'),
                                'field' => $field,
                                'values' => $values,
                                'selectedValue' => $tempStakeholder->is_customer
                            ]
                        )->render();
                    }

                    break;
                case 'is_kin':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->is_kin = !$tempStakeholder->is_kin;
                        $tempStakeholder->save();

                        $values = ['FALSE' => 'No', 'TRUE' => 'Yes'];
                        $response =  view(
                            'app.components.input-select-fields',
                            [
                                'id' => $request->get('id'),
                                'field' => $field,
                                'values' => $values,
                                'selectedValue' => $tempStakeholder->is_kin
                            ]
                        )->render();
                    }

                    break;
                case 'is_vendor':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->is_vendor = !$tempStakeholder->is_vendor;
                        $tempStakeholder->save();

                        $values = ['FALSE' => 'No', 'TRUE' => 'Yes'];
                        $response =  view(
                            'app.components.input-select-fields',
                            [
                                'id' => $request->get('id'),
                                'field' => $field,
                                'values' => $values,
                                'selectedValue' => $tempStakeholder->is_vendor
                            ]
                        )->render();
                    }
                    break;
                case 'country':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->country = $request->get('value');

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
                case 'state':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->state = $request->get('value');

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
                case 'city':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->city = $request->get('value');

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
                case 'nationality':
                    if ($request->get('updateValue') == 'true') {

                        $tempStakeholder->nationality = $request->get('value');

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
                    'attachment' => 'required|mimes:xlsx'
                ]);
                $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error
                TempStakeholder::query()->truncate();
                $import = new StakeholdersImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.stakeholders.storePreview', ['site_id' => $site_id]);
            } else {
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
        DB::transaction(function () use ($request, $site_id) {
            $validator = \Validator::make($request->all(), [
                'fields.*' => 'required',
            ], [
                'fields.*.required' => 'Must Select all Fields',
                'fields.*.distinct' => 'Field can not be duplicated',

            ]);

            $validator->validate();
            $model = new TempStakeholder();
            $tempdata = $model->cursor();
            $tempCols = $model->getFillable();

            $stakeholder = [];
            $parentsCnics = [];
            $parentsRelations = [];

            foreach ($tempdata as $key => $items) {
                foreach ($tempCols as $k => $field) {
                    $data[$key][$field] = $items[$tempCols[$k]];
                }
                $data[$key]['site_id'] = decryptParams($site_id);
                $data[$key]['is_imported'] = true;

                // if ($data[$key]['parent_cnic'] != null && $data[$key]['parent_cnic'] != "null") {
                //     $is_kins[$key] = true;
                //     $parentsCnics[$key] = explode(',', json_decode($data[$key]['parent_cnic']));
                //     $parentsRelations[$key] = explode(',', $data[$key]['relation']);
                // } else {
                //     $is_kins[$key] = false;
                // }
                // $data[$key]['parent_id'] = 0;
                // $data[$key]['relation'] = null;

                if ($data[$key]['country'] != "null") {
                    $country = Country::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['country']))->first();
                    if ($country) {
                        $data[$key]['country_id'] = $country->id;
                    } else {
                        $data[$key]['country_id'] = 1;
                    }
                }

                if ($data[$key]['city'] != "null") {
                    $city = City::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['city']))->first();
                    if ($city) {
                        $data[$key]['city_id'] = $city->id;
                    }
                }
                if ($data[$key]['state'] != "null") {
                    $state = State::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['state']))->first();
                    if ($state) {
                        $data[$key]['state_id'] = $state->id;
                    }
                }
                unset($data[$key]['parent_cnic']);
                unset($data[$key]['is_dealer']);
                unset($data[$key]['is_vendor']);
                // unset($data[$key]['is_kin']);
                unset($data[$key]['is_customer']);
                unset($data[$key]['country']);
                unset($data[$key]['state']);
                unset($data[$key]['city']);

                $stakeholder = Stakeholder::create($data[$key]);

                $stakeholdertype = [
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => 'C',
                        'stakeholder_code' => 'C-00' . $stakeholder->id,
                        'status' => $items['is_customer'] ? 1 : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => 'V',
                        'stakeholder_code' => 'V-00' . $stakeholder->id,
                        'status' => $items['is_vendor'] ? 1 : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => 'D',
                        'stakeholder_code' => 'D-00' . $stakeholder->id,
                        'status' => $items['is_dealer'] ? 1 : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => 'K',
                        'stakeholder_code' => 'K-00' . $stakeholder->id,
                        'status' => $items['is_kin'] ? 1 : 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => 'L',
                        'stakeholder_code' => 'L-00' . $stakeholder->id,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                ];

                $stakeholder_type = StakeholderType::insert($stakeholdertype);
                // if ($is_kins[$key]) {
                //     foreach ($parentsCnics[$key] as $c => $cnics) {
                //         $parent = Stakeholder::where('cnic', trim($cnics))->first();

                //         if ($parent == null) {
                //             return redirect()->route('sites.stakeholders.storePreview', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger('Stakeholder With requested parent ' . $cnics . ' cnic does not exist. Please Add it First as a Parent Stakeholder.');
                //         }
                //         $kins[$c]['site_id'] = decryptParams($site_id);
                //         $kins[$c]['stakeholder_id'] = $parent->id;
                //         $kins[$c]['kin_id'] = $stakeholder->id;
                //         $kins[$c]['relation'] = Str::replace('"', '', trim($parentsRelations[$key][$c]));

                //         StakeholderNextOfKin::create($kins[$c]);
                //     }

                //     $is_kins[$key] = false;
                // }
            }
        });
        TempStakeholder::query()->truncate();

        return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
    }
}
