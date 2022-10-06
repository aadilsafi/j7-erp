<?php

namespace App\Services\FileManagements\FileActions\Cancellation;

use App\Models\FileBuyBack;
use App\Models\FileBuyBackLabelsAttachment;
use App\Models\FileCanecllation;
use App\Models\FileCanecllationAttachment;
use App\Models\Unit;
use App\Models\User;
use App\Models\Stakeholder;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\URL;
use App\Models\FileRefundAttachment;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Notification;
use App\Notifications\FileRefundNotification;
use App\Services\FileManagements\FileActions\BuyBack\BuyBackInterface as BuyBackInterface;

use Illuminate\Support\Facades\DB;

class CancellationService implements CancellationInterface
{

    public function model()
    {
        return new FileCanecllation();
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
            $data = [
                'site_id' => decryptParams($site_id),
                'file_id' => $inputs['file_id'],
                'unit_id' => $inputs['unit_id'],
                'stakeholder_id' => $inputs['customer_id'],
                'unit_data' => json_encode(Unit::find($inputs['unit_id'])),
                'stakeholder_data' => json_encode(Stakeholder::find($inputs['customer_id'])),
                'amount_to_be_refunded' => str_replace( ',', '', $inputs['amount_to_be_refunded']) ,
                'payment_due_date' => $inputs['payment_due_date'],
                'amount_remarks' => $inputs['amount_remarks'],
                'status' => 0,
                'cancellation_charges' =>$inputs['cancellation_charges'],
                'comments' => $inputs['comments'],
            ];

            $unit_data = Unit::find($inputs['unit_id']);
            $stakeholder_data = Stakeholder::find($inputs['customer_id']);

            $fileCancel = $this->model()->create($data);

            $currentURL = URL::current();
            $authRoleId = auth()->user()->roles->pluck('id')->first();
            $approvePermission = (new Role())->find($authRoleId)->hasPermissionTo('sites.file-managements.file-buy-back.approve');
            $permission = (new Permission())->where('name', 'sites.file-managements.file-buy-back.approve')->first();

            $approvePermission = $permission->roles;

            $notificationData = [
                'title' => 'File Refund Notificaton',
                'message' => 'File Attachments are not Attached against Unit number (' . $unit_data->floor_unit_number . ') of customer (' . $stakeholder_data->full_name . ').',
                'description' => 'File Attachments are not Attached against Unit number (' . $unit_data->floor_unit_number . ') of customer (' . $stakeholder_data->full_name . ').',
                'url' => str_replace('/store', '', $currentURL),
            ];


            if (isset($inputs['checkAttachment'])) {

                for ($i = 0; $i < count($inputs['attachments']); $i++) {

                    $refund_attachment_data = [
                        'site_id' => decryptParams($site_id),
                        'file_cancellation_id' => $fileCancel->id,
                        'label' => $inputs['attachments'][$i]['attachment_label'],
                    ];
                    $cancel_attachment = (new FileCanecllationAttachment())->create($refund_attachment_data);
                    $cancel_attachment->addMedia($inputs['attachments'][$i]['image'])->toMediaCollection('file_cancel_attachments');
                }
            } else {
                $specificUsers = collect();
                foreach ($approvePermission as $role) {
                    $specificUsers = $specificUsers->merge(User::role($role->name)->whereNot('id', Auth::user()->id)->get());
                }
                Notification::send($specificUsers, new FileRefundNotification($notificationData));
            }
            return $fileCancel;
        });
    }

    public function update($site_id, $id, $inputs)
    {
    }

    public function destroy($site_id, $inputs)
    {
    }
}
