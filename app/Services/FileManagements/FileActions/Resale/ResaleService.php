<?php

namespace App\Services\FileManagements\FileActions\Resale;

use App\Models\Unit;
use App\Models\User;
use App\Models\FileResale;
use App\Models\Stakeholder;
use App\Models\FileManagement;
use App\Models\StakeholderType;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\URL;
use App\Models\FileResaleAttachment;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Models\FileBuyBackLabelsAttachment;

use Illuminate\Support\Facades\Notification;
use App\Notifications\FileRefundNotification;
use App\Services\FileManagements\FileActions\Resale\ResaleInterface as ResaleInterface;
use Str;

class ResaleService implements ResaleInterface
{

    public function model()
    {
        return new FileResale();
    }

    // Get
    public function getByAll($site_id)
    {
        return $this->model()->whereSiteId($site_id)->get();
    }

    public function getById($site_id, $id)
    {
    }

    // Store
    public function store($site_id, $inputs)
    {
        DB::transaction(function () use ($site_id, $inputs) {
            $file = FileManagement::find($inputs['file_id']);
            $serail_no = $this->model()::max('id') + 1;
            $serail_no =  sprintf('%03d', $serail_no);
            $data = [
                'site_id' => decryptParams($site_id),
                'file_id' => $inputs['file_id'],
                'unit_id' => $inputs['unit_id'],
                'sales_plan_id' => $file->sales_plan_id,
                'stakeholder_id' => $inputs['customer_id'],
                'unit_data' => json_encode(Unit::find($inputs['unit_id'])),
                'stakeholder_data' => json_encode(Stakeholder::find($inputs['customer_id'])),
                'new_resale_rate' => str_replace(',', '', $inputs['new_resale_rate']),
                'premium_demand' => str_replace(',', '', $inputs['premium_demand']),
                'marketing_service_charges' => str_replace(',', '', $inputs['marketing_service_charges']),
                'amount_remarks' => Str::limit($inputs['amount_remarks'],200,''),
                'status' => 0,
                'created_date' => $inputs['created_date'] . date(' H:i:s'),
                'comments' => $inputs['comments'],
                'serial_no' => 'FRS-'.$serail_no,
            ];

            $unit_data = Unit::find($inputs['unit_id']);
            $stakeholder_data = Stakeholder::find($inputs['customer_id']);

            $resalefile = $this->model()->create($data);

            $currentURL = URL::current();
            $authRoleId = auth()->user()->roles->pluck('id')->first();
            $approvePermission = (new Role())->find($authRoleId)->hasPermissionTo('sites.file-managements.file-resale.approve');
            $permission = (new Permission())->where('name', 'sites.file-managements.file-resale.approve')->first();

            $approvePermission = $permission->roles;

            return $resalefile;
        });
    }

    public function update($site_id, $id, $inputs)
    {
    }

    public function destroy($site_id, $inputs)
    {
    }
}
