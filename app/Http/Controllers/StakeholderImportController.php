<?php

namespace App\Http\Controllers;

use App\DataTables\ImportCompanyStakeholdersDataTable;
use App\DataTables\ImportStakeholdersDataTable;
use App\Imports\CompanyStakeholdersImport;
use App\Imports\IndividualStakeholdersImport;
use Illuminate\Http\Request;
use App\Imports\StakeholdersImport;
use App\Jobs\ImportStakeholders;
use App\Models\BacklistedStakeholder;
use App\Models\City;
use App\Models\Country;
use App\Models\Stakeholder;
use App\Models\StakeholderType;
use App\Models\State;
use App\Models\TempCompanyStakeholder;
use App\Models\TempStakeholder;
use DB;
use Exception;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\HeadingRowImport;
use Redirect;

class StakeholderImportController extends Controller
{
    // importing stakeholder data

    public function getInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempStakeholder = (new TempStakeholder())->find((int)$request->get('id'));

            $response = view('app.components.unit-preview-cell', [
                'field' => $field,
                'id' => $request->get('id'), 'input_type' => $request->get('inputtype'),
                'value' => $request->get('value')
            ])->render();
            return apiSuccessResponse($response);
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

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx',
                    'stakeholder_as' => 'required|in:i,c'
                ]);
                // $headings = (new HeadingRowImport)->toArray($request->file('attachment'));
                // dd(array_intersect($model->getFillable(),$headings[0][0]));
                //validate header row and return with error
                if ($request->get('stakeholder_as') == 'i') {
                    $model = new TempStakeholder();

                    TempStakeholder::query()->truncate();
                    $import = new IndividualStakeholdersImport($model->getFillable());
                    $import->import($request->file('attachment'));
                } else {
                    $model = new TempCompanyStakeholder();
                    TempCompanyStakeholder::query()->truncate();
                    $import = new CompanyStakeholdersImport($model->getFillable());
                    $import->import($request->file('attachment'));
                }
                return redirect()->route('sites.stakeholders.storePreview', ['site_id' => $site_id, 'type' => $request->get('stakeholder_as')]);
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

    public function storePreview(Request $request, $site_id, $type)
    {
        if ($type == 'i') {
            $model = new TempStakeholder();
            $dataTable = new ImportStakeholdersDataTable($site_id);
        } else {
            $model = new TempCompanyStakeholder();
            $dataTable = new ImportCompanyStakeholdersDataTable($site_id);
        }

        if ($model->count() == 0) {
            return redirect()->route('sites.stakeholders.index', ['site_id' => $site_id])->withSuccess('No Data Found');
        } else {

            $required = [
                'full_name',
                'father_name',
                'cnic',
                'contact',
                'address',
                'is_dealer',
                'is_vendor',
                'is_customer'
            ];
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
                'required_fields' => $required,
                'type' => $type,
            ];
            return $dataTable->with($data)->render('app.sites.stakeholders.importFloorsPreview', $data);
        }
    }

    public function saveImport(Request $request, $site_id, $type)
    {
        // try {

            // $validator = \Validator::make($request->all(), [
            //     'fields.*' => 'required',
            // ], [
            //     'fields.*.required' => 'Must Select all Fields',
            //     'fields.*.distinct' => 'Field can not be duplicated',

            // ]);

            // $validator->validate();
            $model = new TempCompanyStakeholder();
            $tempdata = $model->cursor();
            $tempCols = $model->getFillable();

            $stakeholder = [];
            $parentsCnics = [];
            $parentsRelations = [];

            foreach ($tempdata->chunk(30) as $Importkey => $importData) {

                DB::transaction(function () use ($site_id, $importData, $Importkey, $tempCols) {
                    foreach ($importData as $key => $items) {
                        $data[$key]['site_id'] = decryptParams($site_id);
                        $data[$key]['stakeholder_as'] = 'c';

                        foreach ($tempCols as $k => $field) {
                            $data[$key][$field] = $items[$tempCols[$k]];
                        }
                        $data[$key]['is_imported'] = true;
                        $data[$key]['full_name'] = $data[$key]['company_name'];
                        $data[$key]['cnic'] = $data[$key]['registration'];
                        if ($data[$key]['origin'] != "null") {
                            $data[$key]['origin'] = 167;
                        } else {
                            $nationality = Country::whereRaw('LOWER(name) = (?)', [strtolower($data[$key]['origin'])])->first();
                            if ($nationality) {
                                $data[$key]['origin'] = $nationality->id;
                            } else {
                                $data[$key]['origin'] = 167;
                            }
                        }

                        // residential address 

                        if ($data[$key]['residential_country'] != "null") {
                            $country = Country::whereRaw('LOWER(name) = (?)', [strtolower($data[$key]['residential_country'])])->first();
                            if ($country) {
                                $data[$key]['residential_country_id'] = $country->id;
                            } else {
                                $data[$key]['residential_country_id'] = 167;
                            }
                        }

                        if ($data[$key]['residential_state'] != "null") {
                            $state = State::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['residential_state']))->first();
                            if ($state) {
                                $data[$key]['residential_state_id'] = $state->id;
                            } else {
                                $data[$key]['residential_state_id'] = 0;
                            }
                        }

                        if ($data[$key]['residential_city'] != "null") {
                            $city = City::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['residential_city']))->first();
                            if ($city) {
                                $data[$key]['residential_city_id'] = $city->id;
                            } else {
                                $data[$key]['residential_city_id'] = 0;
                            }
                        }

                        if ($data[$key]['same_address_for_mailing'] == 'yes') {
                            $data[$key]['mailing_address'] = $data[$key]['residential_address'];
                            $data[$key]['mailing_address_type'] = $data[$key]['residential_address_type'];
                            $data[$key]['mailing_country_id'] = $data[$key]['residential_country_id'];
                            $data[$key]['mailing_state_id'] = $data[$key]['residential_state_id'];
                            $data[$key]['mailing_city_id'] = $data[$key]['residential_city_id'];
                            $data[$key]['mailing_postal_code'] = $data[$key]['residential_postal_code'];
                        } else {
                            // mailing address 

                            if ($data[$key]['mailing_country'] != "null") {
                                $country = Country::whereRaw('LOWER(name) = (?)', [strtolower($data[$key]['mailing_country'])])->first();
                                if ($country) {
                                    $data[$key]['mailing_country_id'] = $country->id;
                                } else {
                                    $data[$key]['mailing_country_id'] = 167;
                                }
                            }

                            if ($data[$key]['mailing_state'] != "null") {
                                $state = State::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['mailing_state']))->first();
                                if ($state) {
                                    $data[$key]['mailing_state_id'] = $state->id;
                                }
                            }

                            if ($data[$key]['mailing_city'] != "null") {
                                $city = City::whereRaw('LOWER(name) = (?)', strtolower($data[$key]['mailing_city']))->first();
                                if ($city) {
                                    $data[$key]['mailing_city_id'] = $city->id;
                                }
                            }
                        }
                        

                        $data[$key]['created_at'] = $items->created_at;
                        $data[$key]['updated_at'] = $items->updated_at;

                        unset($data[$key]['company_name']);
                        unset($data[$key]['is_dealer']);
                        unset($data[$key]['is_vendor']);
                        unset($data[$key]['is_customer']);
                        unset($data[$key]['is_kin']);
                        unset($data[$key]['regsitration']);
                        unset($data[$key]['residential_country']);
                        unset($data[$key]['residential_state']);
                        unset($data[$key]['residential_city']);
                        unset($data[$key]['mailing_country']);
                        unset($data[$key]['mailing_state']);
                        unset($data[$key]['mailing_city']);
                        unset($data[$key]['same_address_for_mailing']);

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
                                'status' => 1,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        ];

                        $stakeholder_type = StakeholderType::insert($stakeholdertype);
                    }
                });

            }
            // ImportStakeholders::dispatch($site_id);

            return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess('Data will be imported Shortly.');
        // } catch (\Throwable $th) {
        //     return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id))])->withdanger('Somethings Went wrong');
        // }
    }
}
