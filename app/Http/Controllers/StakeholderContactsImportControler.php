<?php

namespace App\Http\Controllers;

use App\DataTables\ImportStakeholdersContactsDataTable;
use App\DataTables\ImportStakeholdersKinsDataTable;
use App\Imports\StakeholderConatctsImport;
use App\Imports\StakeholderKinsImport;
use App\Models\BacklistedStakeholder;
use App\Models\Stakeholder;
use App\Models\StakeholderContact;
use App\Models\StakeholderNextOfKin;
use App\Models\TempKins;
use App\Models\TempStakeholder;
use App\Models\TempStakeholderContact;
use DB;
use Exception;
use Illuminate\Http\Request;
use Redirect;

class StakeholderContactsImportControler extends Controller
{

    public function ImportPreview(Request $request, $site_id)
    {
        try {
            $model = new TempStakeholderContact();

            if ($request->hasfile('attachment')) {
                $request->validate([
                    'attachment' => 'required|mimes:xlsx'
                ]);

                TempStakeholderContact::query()->truncate();
                $import = new StakeholderConatctsImport($model->getFillable());
                $import->import($request->file('attachment'));

                return redirect()->route('sites.stakeholders.contacts.storePreview', ['site_id' => $site_id]);
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
        $model = new TempStakeholderContact();

        if ($model->count() == 0) {
            return redirect()->route('sites.stakeholders.index', ['site_id' => $site_id])->withSuccess('No Data Found');
        } else {


            $dataTable = new ImportStakeholdersContactsDataTable($site_id);
            $data = [
                'site_id' => decryptParams($site_id),
                'final_preview' => true,
                'preview' => false,
                'db_fields' =>  $model->getFillable(),
            ];
            return $dataTable->with($data)->render('app.sites.stakeholders.import.importContactsPreview', $data);
        }
    }

    public function saveImport(Request $request, $site_id)
    {
        DB::transaction(function () use ($request, $site_id) {

            $model = new TempStakeholderContact();
            $tempdata = $model->cursor();
            $tempCols = $model->getFillable();

            foreach ($tempdata as $key => $items) {
                foreach ($tempCols as $k => $field) {
                    $data[$key][$field] = $items[$tempCols[$k]];
                }
                // $data[$key]['site_id'] = decryptParams($site_id);
                $data[$key]['is_imported'] = true;

                $stakeholder = Stakeholder::where('cnic', $data[$key]['stakeholder_cnic'])->first();
                $data[$key]['stakeholder_id'] = $stakeholder->id;

                unset($data[$key]['stakeholder_cnic']);
                unset($data[$key]['kin_cnic']);

                $contact = StakeholderContact::create($data[$key]);
                //    dd($contact);
            }
        });
        TempStakeholderContact::query()->truncate();

        return redirect()->route('sites.stakeholders.index', ['site_id' => encryptParams(decryptParams($site_id))])->withSuccess(__('lang.commons.data_saved'));
    }

    public function getInput(Request $request)
    {
        try {
            $field = $request->get('field');
            $tempData = (new TempKins())->find((int)$request->get('id'));

            $validator = \Validator::make($request->all(), [
                'value' => 'required',
            ]);

            if ($validator->fails()) {
                return apiErrorResponse($validator->errors()->first('value'));
            }

            switch ($field) {
                case 'stakeholder_cnic':
                    if ($request->get('updateValue') == 'true') {
                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|exists:stakeholders,cnic',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $blacklisted = BacklistedStakeholder::where('cnic', $request->get('value'))
                            ->first();
                        if ($blacklisted) {
                            return apiErrorResponse('This CNIC is Blacklisted.');
                        }
                        $tempData->stakeholder_cnic = $request->get('value');

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

                case 'kin_cnic':
                    if ($request->get('updateValue') == 'true') {
                        $validator = \Validator::make($request->all(), [
                            'value' => 'required|exists:stakeholders,cnic',
                        ]);

                        if ($validator->fails()) {
                            return apiErrorResponse($validator->errors()->first('value'));
                        }
                        $blacklisted = BacklistedStakeholder::where('cnic', $request->get('value'))
                            ->first();
                        if ($blacklisted) {
                            return apiErrorResponse('This CNIC is Blacklisted.');
                        }
                        $tempData->kin_cnic = $request->get('value');

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

                        $tempData->relation = $request->get('value');

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
            $tempData->save();
            return apiSuccessResponse($response);
        } catch (Exception $ex) {
            return apiErrorResponse($ex->getMessage());
        }
    }
}