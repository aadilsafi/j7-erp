<?php

namespace App\Services\FileManagements\FileActions\BuyBack;

use App\Models\Unit;
use App\Models\User;
use App\Models\FileBuyBack;
use App\Models\Stakeholder;
use App\Models\FileManagement;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\URL;
use App\Models\FileRefundAttachment;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Models\FileBuyBackLabelsAttachment;
use Illuminate\Support\Facades\Notification;

use App\Notifications\FileRefundNotification;
use App\Services\FileManagements\FileActions\BuyBack\BuyBackInterface as BuyBackInterface;

class BuyBackService implements BuyBackInterface
{

    public function model()
    {
        return new FileBuyBack();
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
            $data = [
                'site_id' => decryptParams($site_id),
                'file_id' => $inputs['file_id'],
                'unit_id' => $inputs['unit_id'],
                'sales_plan_id'=>$file->sales_plan_id,
                'stakeholder_id' => $inputs['customer_id'],
                'unit_data' => json_encode(Unit::find($inputs['unit_id'])),
                'stakeholder_data' => json_encode(Stakeholder::find($inputs['customer_id'])),
                'amount_to_be_refunded' => str_replace( ',', '', $inputs['amount_to_be_refunded']) ,
                'payment_due_date' => $inputs['payment_due_date'],
                'amount_remarks' => $inputs['amount_remarks'],
                'status' => 0,
                'amount_profit' =>$inputs['amount_profit'],
                'comments' => $inputs['comments'],
            ];

            $unit_data = Unit::find($inputs['unit_id']);
            $stakeholder_data = Stakeholder::find($inputs['customer_id']);

            $buyBackfile = $this->model()->create($data);

            $currentURL = URL::current();
            $authRoleId = auth()->user()->roles->pluck('id')->first();
            $approvePermission = (new Role())->find($authRoleId)->hasPermissionTo('sites.file-managements.file-buy-back.approve');
            $permission = (new Permission())->where('name', 'sites.file-managements.file-buy-back.approve')->first();

            $approvePermission = $permission->roles;

            $notificationData = [

                'title' => 'File Buy Back Notificaton',
                'message' => 'File Attachments are not Attached against Unit number (' . $unit_data->floor_unit_number . ') of customer (' . $stakeholder_data->full_name . ').',
                'description' => 'File Attachments are not Attached against Unit number (' . $unit_data->floor_unit_number . ') of customer (' . $stakeholder_data->full_name . ').',
                'url' => str_replace('/store', '', $currentURL),
            ];


            if (isset($inputs['checkAttachment'])) {

                for ($i = 0; $i < count($inputs['attachments']); $i++) {

                    $refund_attachment_data = [
                        'site_id' => decryptParams($site_id),
                        'file_buy_back_id' => $buyBackfile->id,
                        'label' => $inputs['attachments'][$i]['attachment_label'],
                    ];
                    $refund_attachment = (new FileBuyBackLabelsAttachment())->create($refund_attachment_data);
                    $refund_attachment->addMedia($inputs['attachments'][$i]['image'])->toMediaCollection('file_buy_back_attachments');
                }
                changeImageDirectoryPermission();
            } else {
                $specificUsers = collect();
                foreach ($approvePermission as $role) {
                    $specificUsers = $specificUsers->merge(User::role($role->name)->whereNot('id', Auth::user()->id)->get());
                }
                Notification::send($specificUsers, new FileRefundNotification($notificationData));
            }
            return $buyBackfile;
        });
    }

    public function update($site_id, $id, $inputs)
    {
    }

    public function destroy($site_id, $inputs)
    {
    }
}
