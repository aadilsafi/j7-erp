<?php

namespace App\Http\Controllers;

use App\DataTables\ImportBanksDataTable;
use App\Imports\BanksImport;
use App\Models\Bank;
use App\Models\TempBank;
use Illuminate\Http\Request;
use Maatwebsite\Excel\HeadingRowImport;
use Redirect;

class BankController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getBank(Request $request)
    {
        $bank = Bank::where('id', $request->id)->first();
        return response()->json([
            'success' => true,
            'bank' => $bank,
        ], 200);
    }

    public function ImportPreview(Request $request, $site_id)
    {
        try {
            $model = new TempBank();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx'
                ]);
                // $headings = (new HeadingRowImport())->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error
                TempBank::query()->truncate();
                $import = new BanksImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.banks.storePreview', ['site_id' => $site_id]);
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
        $model = new TempBank();

        if ($model->count() == 0) {
            return redirect()->route('sites.floors.index', ['site_id' => $site_id])->withSuccess(__('lang.commons.data_saved'));
        } else {
            $dataTable = new ImportBanksDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
            ];
            return $dataTable->with($data)->render('app.sites.banks.importBanksPreview', $data);
        }
    }

    public function saveImport(Request $request, $site_id)
    {
        // DB::transaction(function () use ($request, $site_id) {
        $validator = \Validator::make($request->all(), [
            'fields.*' => 'required|distinct',
        ], [
            'fields.*.required' => 'Must Select all Fields',
            'fields.*.distinct' => 'Field can not be duplicated',

        ]);

        $validator->validate();
        $model = new TempStakeholder();
        $tempdata = $model->cursor();
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
        }

        TempStakeholder::query()->truncate();
        return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
        // });
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
                            'value' => 'required|digits:13|unique:stakeholders,cnic',
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
}
