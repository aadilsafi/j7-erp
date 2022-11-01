<?php

namespace App\Http\Controllers;

use App\DataTables\ImportUnitTypesDataTable;
use App\DataTables\TypesDataTable;
use App\Exceptions\GeneralException;
use App\Http\Requests\types\{
    storeRequest as typeStoreRequest,
    updateRequest as typeUpdateRequest
};
use App\Imports\TypesImport;
use App\Imports\TypesImportSimple;
use App\Models\TempUnitType;
use App\Models\Type;
use App\Services\CustomFields\CustomFieldInterface;
use App\Services\Interfaces\{
    UnitTypeInterface
};
use Exception;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\HeadingRowImport;
use Redirect;

class TypeController extends Controller
{

    private $unitTypeInterface;
    private $customFieldInterface;

    public function __construct(UnitTypeInterface $unitTypeInterface, CustomFieldInterface $customFieldInterface)
    {
        $this->unitTypeInterface = $unitTypeInterface;
        $this->customFieldInterface = $customFieldInterface;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(TypesDataTable $dataTable, $site_id)
    {
        $data = [
            'site_id' => $site_id
        ];

        return $dataTable->with($data)->render('app.sites.types.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, $site_id)
    {
        abort_if(request()->ajax(), 403);

        $site_id = decryptParams($site_id);

        $customFieldsHtml = [];
        $customFields = $this->customFieldInterface->getAllByModel($site_id, get_class($this->unitTypeInterface->model()));
        $customFields = collect($customFields)->sortBy('order');
        $customFields = generateCustomFields($customFields);

        $data = [
            'site_id' => $site_id,
            'types' => $this->unitTypeInterface->getAllWithTree($site_id),
            'customFields' => $customFields
        ];

        return view('app.sites.types.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(typeStoreRequest $request, $site_id)
    {
        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->unitTypeInterface->store($site_id, $inputs);
                return redirect()->route('sites.types.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
            } else {
                abort(403);
            }
        } catch (GeneralException $ex) {
            return redirect()->route('sites.types.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . sqlErrorMessagesByCode($ex->getCode()));
        } catch (Exception $ex) {
            return redirect()->route('sites.types.index', ['site_id' => encryptParams(decryptParams($site_id))])->withDanger(__('lang.commons.something_went_wrong') . ' ' . sqlErrorMessagesByCode($ex->getCode()));
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
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);
        try {
            $type = $this->unitTypeInterface->getById($id);

            if ($type && !empty($type)) {

                $data = [
                    'site_id' => $site_id,
                    'id' => $id,
                    'types' => $this->unitTypeInterface->getAllWithTree($site_id),
                    'type' => $type,
                ];

                return view('app.sites.types.edit', $data);
            }

            return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withWarning(__('lang.commons.data_not_found'));
        } catch (Exception $ex) {
            return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . sqlErrorMessagesByCode($ex->getCode()));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(typeUpdateRequest $request, $site_id, $id)
    {
        $site_id = decryptParams($site_id);
        $id = decryptParams($id);

        try {
            if (!request()->ajax()) {
                $inputs = $request->validated();
                $record = $this->unitTypeInterface->update($site_id, $inputs, $id);
                return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_updated'));
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function destroySelected(Request $request, $site_id)
    {
        try {
            $site_id = decryptParams($site_id);
            if (!request()->ajax()) {
                if ($request->has('chkRole')) {

                    $record = $this->unitTypeInterface->destroy($site_id, encryptParams($request->chkRole));

                    if ($record) {
                        return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withSuccess(__('lang.commons.data_deleted'));
                    } else {
                        return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.data_not_found'));
                    }
                }
            } else {
                abort(403);
            }
        } catch (Exception $ex) {
            return redirect()->route('sites.types.index', ['site_id' => encryptParams($site_id)])->withDanger(__('lang.commons.something_went_wrong') . ' ' . $ex->getMessage());
        }
    }

    public function getTypeInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempType = (new TempUnitType())->find((int)$request->get('id'));

            switch ($field) {
                case 'name':
                    if ($request->get('updateValue') == 'true') {
                        $tempType->name = $request->get('value');

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

                case 'parent_type_name':
                    if ($request->get('updateValue') == 'true') {

                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|exists:App\Models\TempUnitType,unit_type_slug',
                        ], [
                            'value.exists' => 'This Value does not Exists. temp'
                        ]);
                        // $validator2 = \Validator::make($request->all(), [
                        //     'value' => 'required|exists:App\Models\Type,slug',
                        // ], [
                        //     'value.exists' => 'This Value does not Exists.'
                        // ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }

                        $tempType->parent_type_name = $request->get('value');

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

                case 'unit_type_slug':
                    if ($request->get('updateValue') == 'true') {
                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|unique:types,slug',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $validator2 = \Validator::make($request->all(), [
                            'value' => [
                                Rule::unique('temp_unit_types', 'unit_type_slug')->ignore($request->get('id'))
                            ],
                        ]);

                        if ($validator2->fails()) {
                            return apiErrorResponse($validator2->errors()->first('value'));
                        }
                        $tempType->unit_type_slug = $request->get('value');

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
            $tempType->save();
            return apiSuccessResponse($response);
        } catch (Exception $ex) {
            return apiErrorResponse($ex->getMessage());
        }
    }

    public function ImportPreview(Request $request, $site_id)
    {
        try {
            $model = new TempUnitType();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx'
                ]);


                $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error
                TempUnitType::query()->truncate();
                $import = new TypesImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.types.storePreview', ['site_id' => $site_id]);
            } else {
                return Redirect::back()->withDanger('Select File to Import');
            }
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {

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
        $model = new TempUnitType();
        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
        } else {
            $dataTable = new ImportUnitTypesDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
            ];
            return $dataTable->with($data)->render('app.sites.types.importTypesPreview', $data);
        }
    }

    public function saveImport(Request $request, $site_id)
    {
        $validator = \Validator::make($request->all(), [
            'fields.*' => 'required|distinct',
        ], [
            'fields.*.required' => 'Must Select all Fields',
            'fields.*.distinct' => 'Field can not be duplicated',

        ]);

        $validator->validate();

        $model = new TempUnitType();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();
        $accountCode = addAccountCodes(get_class(new Type()));

        foreach ($tempdata as $key => $items) {
            foreach ($request->fields as $k => $field) {
                $data[$key][$field] = $items[$tempCols[$k]];
            }

            $data[$key]['site_id'] = decryptParams($site_id);
            $data[$key]['slug'] = $items->unit_type_slug;

            $data[$key]['status'] = true;

            if ($data[$key]['parent_type_name'] != 'null') {
                $parent = Type::where('slug', $data[$key]['parent_type_name'])->first();
                if ($parent) {
                    $data[$key]['parent_id'] = $parent->id;
                } else {
                    $data[$key]['parent_id'] = 0;
                }
            } else {
                $data[$key]['parent_id'] = 0;
            }
            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();


            if (!is_null($accountCode) && $data[$key]['parent_id'] == 0) {

                $data[$key]['account_added'] = true;
                $data[$key]['account_number'] = $accountCode++;
            } else {
                $data[$key]['account_added'] = false;
                $data[$key]['account_number'] = null;
            }
            unset($data[$key]['unit_type_slug']);
            unset($data[$key]['parent_type_name']);

            $types = Type::insert($data[$key]);
        }

        TempUnitType::query()->truncate();

        return redirect()->route('sites.types.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
    }
}
