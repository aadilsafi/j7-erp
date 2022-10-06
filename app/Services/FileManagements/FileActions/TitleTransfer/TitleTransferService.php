<?php

namespace App\Services\FileManagements\FileActions\TitleTransfer;

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
use App\Models\FileTitleTransfer;
use App\Models\FileTitleTransferAttachment;
use Illuminate\Support\Facades\Notification;

use App\Notifications\FileRefundNotification;
use App\Services\FileManagements\FileActions\TitleTransfer\TitleTransferInterface as TitleTransferInterface;

class TitleTransferService implements TitleTransferInterface
{

    public function model()
    {
        return new FileTitleTransfer();
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

            if ($inputs['stackholder']['stackholder_id'] == 0) {
                $transfer_owner_data = [
                    'site_id' => decryptParams($site_id),
                    'full_name' => $inputs['stackholder']['full_name'],
                    'father_name' => $inputs['stackholder']['father_name'],
                    'occupation' => $inputs['stackholder']['occupation'],
                    'designation' => $inputs['stackholder']['designation'],
                    'cnic' => $inputs['stackholder']['cnic'],
                    'ntn' => $inputs['stackholder']['ntn'],
                    'contact' => $inputs['stackholder']['contact'],
                    'address' => $inputs['stackholder']['address'],
                    'comments' => $inputs['stackholder']['comments'],

                ];

                $transfer_person = Stakeholder::create($transfer_owner_data);
                $transfer_person_id = $transfer_person->id;

                $stakeholdertype = [
                    [
                        'stakeholder_id' => $transfer_person_id,
                        'type' => 'C',
                        'stakeholder_code' => 'C-00' . $transfer_person_id,
                        'status' => 1,
                    ],
                    [
                        'stakeholder_id' => $transfer_person_id,
                        'type' => 'V',
                        'stakeholder_code' => 'V-00' . $transfer_person_id,
                        'status' => 0,
                    ],
                    [
                        'stakeholder_id' => $transfer_person_id,
                        'type' => 'D',
                        'stakeholder_code' => 'D-00' . $transfer_person_id,
                        'status' => 0,
                    ],
                    [
                        'stakeholder_id' => $transfer_person_id,
                        'type' => 'K',
                        'stakeholder_code' => 'K-00' . $transfer_person_id,
                        'status' => 0,
                    ],
                    [
                        'stakeholder_id' => $transfer_person_id,
                        'type' => 'L',
                        'stakeholder_code' => 'L-00' . $transfer_person_id,
                        'status' => 1,
                    ]
                ];

                $stakeholder_type = StakeholderType::insert($stakeholdertype);
            } else {
                $transfer_person_id = $inputs['stackholder']['stackholder_id'];
            }



            $data = [
                'site_id' => decryptParams($site_id),
                'file_id' => $inputs['file_id'],
                'unit_id' => $inputs['unit_id'],
                'stakeholder_id' => $inputs['customer_id'],
                'transfer_person_id' => $transfer_person_id,
                'transfer_person_data' => json_encode(Stakeholder::find($transfer_person_id)),
                'unit_data' => json_encode(Unit::find($inputs['unit_id'])),
                'stakeholder_data' => json_encode(Stakeholder::find($inputs['customer_id'])),
                'amount_to_be_paid' => str_replace(',', '', $inputs['amount_to_be_paid']),
                'payment_due_date' => $inputs['payment_due_date'],
                'amount_remarks' => $inputs['amount_remarks'],
                'transfer_rate' => $inputs['transfer_rate'],
                'status' => 0,
                'comments' => $inputs['comments'],
            ];

            $unit_data = Unit::find($inputs['unit_id']);
            $stakeholder_data = Stakeholder::find($inputs['customer_id']);

            $titleTransferfile = $this->model()->create($data);

            $currentURL = URL::current();
            $authRoleId = auth()->user()->roles->pluck('id')->first();
            $approvePermission = (new Role())->find($authRoleId)->hasPermissionTo('sites.file-managements.file-title-transfer.approve');
            $permission = (new Permission())->where('name', 'sites.file-managements.file-title-transfer.approve')->first();

            $approvePermission = $permission->roles;

            $notificationData = [

                'title' => 'File Title Transfer Notificaton',
                'message' => 'File Attachments are not Attached against Unit number (' . $unit_data->floor_unit_number . ') of customer (' . $stakeholder_data->full_name . ').',
                'description' => 'File Attachments are not Attached against Unit number (' . $unit_data->floor_unit_number . ') of customer (' . $stakeholder_data->full_name . ').',
                'url' => str_replace('/store', '', $currentURL),
            ];


            if (isset($inputs['checkAttachment'])) {

                for ($i = 0; $i < count($inputs['attachments']); $i++) {

                    $title_transfer_attachment_data = [
                        'site_id' => decryptParams($site_id),
                        'file_title_transfer_id' => $titleTransferfile->id,
                        'label' => $inputs['attachments'][$i]['attachment_label'],
                    ];
                    $title_transfer_attachment_data_attachment_data = (new FileTitleTransferAttachment())->create($title_transfer_attachment_data);
                    $title_transfer_attachment_data_attachment_data->addMedia($inputs['attachments'][$i]['image'])->toMediaCollection('file_title_transfer_attachments');
                }
            } else {
                $specificUsers = collect();
                foreach ($approvePermission as $role) {
                    $specificUsers = $specificUsers->merge(User::role($role->name)->whereNot('id', Auth::user()->id)->get());
                }
                Notification::send($specificUsers, new FileRefundNotification($notificationData));
            }
            return $titleTransferfile;
        });
    }

    public function update($site_id, $id, $inputs)
    {
    }

    public function destroy($site_id, $inputs)
    {
    }
}
