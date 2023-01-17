<?php

namespace App\Services\FileManagements;

use App\Models\Unit;
use App\Models\SalesPlan;
use App\Models\Stakeholder;
use App\Models\FileManagement;
use App\Models\FileStakeholderConatct;
use App\Models\FileStakeholderContact;
use App\Models\StakeholderContact;
use App\Models\TempFiles;
use App\Models\TempFilesStakeholderContact;
use App\Services\FileManagements\FileManagementInterface;
use Arr;
use Auth;

class FileManagementService implements FileManagementInterface
{

    public function model()
    {
        return new FileManagement();
    }

    // Get
    public function getByAll($site_id)
    {
        return $this->model()->whereSiteId($site_id)->get();
    }

    public function getById($site_id, $id)
    {
        return $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ])->first();
    }

    // Store
    public function store($site_id, $inputs)
    {

        $sales_plan = (new SalesPlan())->with([
            'additionalCosts', 'installments', 'leadSource', 'receipts'
        ])->where([
            'status' => 1,
            'unit_id' => $inputs['application_form']['unit_id'],
        ])->first();


        $serail_no = $this->model()::max('id') + 1;
        $serail_no =  sprintf('%03d', $serail_no);

        $data = [
            'user_id' => Auth::user()->id,
            'site_id' => decryptParams($site_id),
            'unit_id' => $inputs['application_form']['unit_id'],
            'stakeholder_id' => $inputs['application_form']['stakeholder_id'],
            'unit_data' => json_encode(Unit::find($inputs['application_form']['unit_id'])),
            'stakeholder_data' => json_encode(Stakeholder::find($inputs['application_form']['stakeholder_id'])),
            'registration_no' => $inputs['application_form']['registration_no'],
            'application_no' => $inputs['application_form']['application_no'],
            'deal_type' => $inputs['application_form']['deal_type'],
            'status' => 1,
            'file_action_id' => 1,
            'sales_plan_id' => $sales_plan->id,
            'serial_no' => 'UF-' . $serail_no,
            'note_serial_number' => $inputs['application_form']['note_serial_number'],
            // 'created_date' => $sales_plan->created_date,
        ];
        $file = $this->model()->create($data);

        // attach conatct person to file if selected 
        if (isset($inputs['application_form']['contact_persons'])) {
            $file->stakeholderConatcts()->attach($inputs['application_form']['contact_persons'], ['site_id' => decryptParams($site_id)]);
        }
        if (isset($inputs['application_form']['photo'])) {
            $file->addMedia($inputs['application_form']['photo'])->toMediaCollection('application_form_photo');
            changeImageDirectoryPermission();
        }

        return $file;
    }

    public function update($site_id, $id, $inputs)
    {
        $data = [
            'name' => filter_strip_tags($inputs['lead_source_name']),
        ];

        $leadSource = $this->model()->where([
            'site_id' => $site_id,
            'id' => $id,
        ])->update($data);

        return $leadSource;
    }

    public function destroy($site_id, $inputs)
    {
        $this->model()->whereIn('id', $inputs)->delete();

        return true;
    }

    public function saveImport($site_id)
    {
        $model = new TempFiles();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();

        $url = [];

        foreach ($tempdata as $key => $items) {
            foreach ($tempCols as $k => $field) {
                $data[$key][$field] = $items[$tempCols[$k]];
            }

            $salePlan = SalesPlan::where('doc_no', $data[$key]['sales_plan_doc_no'])
                ->first();
            $unitId = $salePlan->unit;
            $stakeholder = $salePlan->stakeholder;

            $serail_no = $this->model()::max('id') + 1;
            $serail_no =  sprintf('%03d', $serail_no);

            $data[$key]['site_id'] = decryptParams($site_id);
            $data[$key]['user_id'] = Auth::user()->id;
            $data[$key]['stakeholder_id'] = $stakeholder->id;
            $data[$key]['stakeholder_data'] = json_encode($stakeholder);
            $data[$key]['sales_plan_id'] = $salePlan->id;
            $data[$key]['unit_id'] = $unitId->id;
            $data[$key]['unit_data'] = json_encode(Unit::find($unitId->id));
            $data[$key]['serial_no'] = 'UF-' . $serail_no;
            $data[$key]['status'] = 1;
            $data[$key]['file_action_id'] = 1;
            $data[$key]['is_imported'] = true;

            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();

            unset($data[$key]['sales_plan_doc_no']);
            unset($data[$key]['stakeholder_cnic']);
            unset($data[$key]['total_price']);
            unset($data[$key]['down_payment_total']);
            unset($data[$key]['sales_plan_approval_date']);
            unset($data[$key]['other_payment_mode_value']);
            unset($data[$key]['online_transaction_no']);
            unset($data[$key]['installment_no']);
            unset($data[$key]['amount']);
            unset($data[$key]['bank_name']);
            unset($data[$key]['image_url']);

            $file = $this->model()->create($data[$key]);
        }
        TempFiles::truncate();
        return $file;
    }

    public function saveFileContactsImport($site_id)
    {
        $model = new TempFilesStakeholderContact();
        $tempdata = $model->cursor();
        $tempCols = $model->getFillable();

        $url = [];

        foreach ($tempdata as $key => $items) {
            foreach ($tempCols as $k => $field) {
                $data[$key][$field] = $items[$tempCols[$k]];
            }

            $file = FileManagement::where('doc_no', $data[$key]['file_doc_no'])->first();
            $data[$key]['site_id'] = decryptParams($site_id);
            // $data[$key]['is_imported'] = true;

            $data[$key]['file_management_id'] = $file->id;
            $data[$key]['stakeholder_contact_id'] = StakeholderContact::where('cnic', $data[$key]['contact_cnic'])->first()->id;
            $data[$key]['created_at'] = now();
            $data[$key]['updated_at'] = now();

            $kinId = Stakeholder::where('cnic', $data[$key]['kin_cnic'])->first()->id;
            $salePlanKins[$file->salePlan->id][] = $kinId;


            unset($data[$key]['file_doc_no']);
            unset($data[$key]['contact_cnic']);
            unset($data[$key]['kin_cnic']);

            $file = FileStakeholderContact::create($data[$key]);
        }
        foreach ($salePlanKins as $key => $value) {
            $salePlan = SalesPlan::find($key);
            $kins = json_decode($salePlan->kin_data);
            foreach ($value as $k => $v) {
                if (Arr::exists($kins, $v)) {
                    continue;
                }
                $kins[] = $v;
            }

            $salePlan->kin_data = json_encode($kins);
            $salePlan->save();
        }

        TempFilesStakeholderContact::truncate();
        return $file;
    }
}
