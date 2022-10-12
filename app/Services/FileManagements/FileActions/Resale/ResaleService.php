<?php

namespace App\Services\FileManagements\FileActions\Resale;

use App\Models\Unit;
use App\Models\User;
use App\Models\FileResale;
use App\Models\Stakeholder;
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

            // if ($inputs['stackholder']['stackholder_id'] == 0) {
            //     $buyer_data = [
            //         'site_id' => decryptParams($site_id),
            //         'full_name' => $inputs['stackholder']['full_name'],
            //         'father_name' => $inputs['stackholder']['father_name'],
            //         'occupation' => $inputs['stackholder']['occupation'],
            //         'designation' => $inputs['stackholder']['designation'],
            //         'cnic' => $inputs['stackholder']['cnic'],
            //         'ntn' => $inputs['stackholder']['ntn'],
            //         'contact' => $inputs['stackholder']['contact'],
            //         'address' => $inputs['stackholder']['address'],
            //         'comments' => $inputs['stackholder']['comments'],
            //     ];

            //     $buyer = Stakeholder::create($buyer_data);
            //     $buyer_id = $buyer->id;

            //     $stakeholdertype = [
            //         [
            //             'stakeholder_id' => $buyer_id,
            //             'type' => 'C',
            //             'stakeholder_code' => 'C-00' . $buyer_id,
            //             'status' => 1,
            //         ],
            //         [
            //             'stakeholder_id' => $buyer_id,
            //             'type' => 'V',
            //             'stakeholder_code' => 'V-00' . $buyer_id,
            //             'status' => 0,
            //         ],
            //         [
            //             'stakeholder_id' => $buyer_id,
            //             'type' => 'D',
            //             'stakeholder_code' => 'D-00' . $buyer_id,
            //             'status' => 0,
            //         ],
            //         [
            //             'stakeholder_id' => $buyer_id,
            //             'type' => 'K',
            //             'stakeholder_code' => 'K-00' . $buyer_id,
            //             'status' => 0,
            //         ],
            //         [
            //             'stakeholder_id' => $buyer_id,
            //             'type' => 'L',
            //             'stakeholder_code' => 'L-00' . $buyer_id,
            //             'status' => 1,
            //         ]
            //     ];

            //     $stakeholder_type = StakeholderType::insert($stakeholdertype);
            // } else {
            //     $buyer_id = $inputs['stackholder']['stackholder_id'];
            // }



            $data = [
                'site_id' => decryptParams($site_id),
                'file_id' => $inputs['file_id'],
                'unit_id' => $inputs['unit_id'],
                'stakeholder_id' => $inputs['customer_id'],
                'unit_data' => json_encode(Unit::find($inputs['unit_id'])),
                'stakeholder_data' => json_encode(Stakeholder::find($inputs['customer_id'])),
                'amount_to_be_refunded' => str_replace(',', '', $inputs['amount_to_be_refunded']),
                'payment_due_date' => $inputs['payment_due_date'],
                'amount_remarks' => $inputs['amount_remarks'],
                'status' => 0,
                'amount_profit' => $inputs['amount_profit'],
                'comments' => $inputs['comments'],
            ];

            $unit_data = Unit::find($inputs['unit_id']);
            $stakeholder_data = Stakeholder::find($inputs['customer_id']);

            $resalefile = $this->model()->create($data);

            $currentURL = URL::current();
            $authRoleId = auth()->user()->roles->pluck('id')->first();
            $approvePermission = (new Role())->find($authRoleId)->hasPermissionTo('sites.file-managements.file-resale.approve');
            $permission = (new Permission())->where('name', 'sites.file-managements.file-resale.approve')->first();

            $approvePermission = $permission->roles;

            $notificationData = [

                'title' => 'File Resale Notificaton',
                'message' => 'File Attachments are not Attached against Unit number (' . $unit_data->floor_unit_number . ') of customer (' . $stakeholder_data->full_name . ').',
                'description' => 'File Attachments are not Attached against Unit number (' . $unit_data->floor_unit_number . ') of customer (' . $stakeholder_data->full_name . ').',
                'url' => str_replace('/store', '', $currentURL),
            ];


            if (isset($inputs['checkAttachment'])) {

                for ($i = 0; $i < count($inputs['attachments']); $i++) {

                    $resale_attachment_data = [
                        'site_id' => decryptParams($site_id),
                        'file_resale_id' => $resalefile->id,
                        'label' => $inputs['attachments'][$i]['attachment_label'],
                    ];
                    $resale_attachment_data = (new FileResaleAttachment())->create($resale_attachment_data);
                    $resale_attachment_data->addMedia($inputs['attachments'][$i]['image'])->toMediaCollection('file_resale_attachments');
                }
            } else {
                $specificUsers = collect();
                foreach ($approvePermission as $role) {
                    $specificUsers = $specificUsers->merge(User::role($role->name)->whereNot('id', Auth::user()->id)->get());
                }
                Notification::send($specificUsers, new FileRefundNotification($notificationData));
            }
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
