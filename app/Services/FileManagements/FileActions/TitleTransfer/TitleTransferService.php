<?php

namespace App\Services\FileManagements\FileActions\TitleTransfer;

use App\Exceptions\GeneralException;
use App\Models\Unit;
use App\Models\User;
use App\Models\FileResale;
use App\Models\Stakeholder;
use App\Models\FileManagement;
use App\Models\StakeholderType;
use App\Models\FileTitleTransfer;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\URL;
use App\Models\FileResaleAttachment;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use App\Models\FileBuyBackLabelsAttachment;
use App\Models\FileTitleTransferAttachment;

use Illuminate\Support\Facades\Notification;
use App\Notifications\FileRefundNotification;
use App\Services\FileManagements\FileActions\TitleTransfer\TitleTransferInterface as TitleTransferInterface;
use App\Services\Stakeholder\Interface\StakeholderInterface;
use App\Utils\Enums\StakeholderTypeEnum;
use Illuminate\Support\Str;

class TitleTransferService implements TitleTransferInterface
{

    private $stakeholderInterface;

    public function __construct(StakeholderInterface $stakeholderInterface)
    {
        $this->stakeholderInterface = $stakeholderInterface;
    }

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

            // if ($inputs['stackholder']['stackholder_id'] == 0) {
            //     $transfer_owner_data = [
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
            //         'countryDetails' => $inputs['stackholder']['countryDetails'],
            //         'optional_contact' => $inputs['stackholder']['optional_contact'],
            //         'OptionalCountryDetails' => $inputs['stackholder']['OptionalCountryDetails'],
            //         'mailing_address' => $inputs['stackholder']['mailing_address'],
            //         'email' => $inputs['stackholder']['email'],
            //         'optional_email' => $inputs['stackholder']['optional_email'],
            //         'country_id' => $inputs['stackholder']['country_id'],
            //         'state_id' => $inputs['stackholder']['state_id'],
            //         'city_id' => $inputs['stackholder']['city_id'],
            //         'stakeholder_as' => "i"
            //     ];



            //     $transfer_person = Stakeholder::create($transfer_owner_data);
            //     $transfer_person_id = $transfer_person->id;

            //     $stakeholdertype = [
            //         [
            //             'stakeholder_id' => $transfer_person_id,
            //             'type' => 'C',
            //             'stakeholder_code' => 'C-00' . $transfer_person_id,
            //             'status' => 1,
            //         ],
            //         [
            //             'stakeholder_id' => $transfer_person_id,
            //             'type' => 'V',
            //             'stakeholder_code' => 'V-00' . $transfer_person_id,
            //             'status' => 0,
            //         ],
            //         [
            //             'stakeholder_id' => $transfer_person_id,
            //             'type' => 'D',
            //             'stakeholder_code' => 'D-00' . $transfer_person_id,
            //             'status' => 0,
            //         ],
            //         [
            //             'stakeholder_id' => $transfer_person_id,
            //             'type' => 'K',
            //             'stakeholder_code' => 'K-00' . $transfer_person_id,
            //             'status' => 0,
            //         ],
            //         [
            //             'stakeholder_id' => $transfer_person_id,
            //             'type' => 'L',
            //             'stakeholder_code' => 'L-00' . $transfer_person_id,
            //             'status' => 1,
            //         ]
            //     ];

            //     $stakeholder_type = StakeholderType::insert($stakeholdertype);
            // } else {
            //     $transfer_person_id = $inputs['stackholder']['stackholder_id'];
            // }


            $stakeholderInput = $inputs['stackholder'];
            $individual = $inputs['individual'];

            $company = $inputs['company'];

            if (isset($individual['cnic']) && $stakeholderInput['stackholder_id'] == 0 && $inputs['stakeholder_as'] == 'i' && $this->stakeholderInterface->model()->where('cnic', $individual['cnic'])->exists()) {
                throw new GeneralException('Stakeholder CNIC already exists');
            }
            if (isset($company['cnic']) && $stakeholderInput['stackholder_id'] == 0 && $inputs['stakeholder_as'] == 'c' && $this->stakeholderInterface->model()->where('cnic', $company['cnic'])->exists()) {
                throw new GeneralException('Company Registration No already exists');
            }


            if (!isset($inputs['stakeholder_as'])) {
                $leadStakeholder = Stakeholder::find($stakeholderInput['stackholder_id']);
                $stakeholder_as = $leadStakeholder->stakeholder_as;
            } else {
                $stakeholder_as = $inputs['stakeholder_as'];
            }
            if ($stakeholder_as == 'i') {
                $stakeholderData = [
                    'full_name' => $individual['full_name'],
                    'father_name' => $individual['father_name'],
                    'occupation' => $individual['occupation'],
                    'designation' => $individual['designation'],
                    'cnic' => $individual['cnic'],
                    'passport_no' => $individual['passport_no'],
                    'ntn' => $individual['ntn'],
                    'email' => $individual['individual_email'],
                    'office_email' => $individual['office_email'],
                    'mobile_contact' => $individual['mobile_contact'],
                    'mobileContactCountryDetails' => $inputs['mobileContactCountryDetails'],
                    'office_contact' => $individual['office_contact'],
                    'OfficeContactCountryDetails' => $inputs['OfficeContactCountryDetails'],
                    'referred_by' => $individual['referred_by'],
                    'source' => $individual['source'] ?? 0,
                    'date_of_birth' => $individual['dob'],
                    'is_local' => isset($individual['is_local']) ? $individual['is_local'] : 0,
                    'nationality' => $individual['nationality'],
                ];
            } else if ($stakeholder_as == 'c') {
                $stakeholderData = [
                    'full_name' => $company['company_name'],
                    'industry' => $company['industry'],
                    'office_contact' => $company['company_office_contact'],
                    'OfficeContactCountryDetails' => $inputs['CompanyOfficeContactCountryDetails'],
                    'mobile_contact' => $company['company_optional_contact'],
                    'mobileContactCountryDetails' => $inputs['companyMobileContactCountryDetails'],
                    'email' => $company['company_email'],
                    'office_email' => $company['company_office_email'],
                    'website' => $company['website'],
                    'parent_company' => $company['parent_company'],
                    'cnic' => $company['registration'],
                    'strn' => $company['strn'],
                    'ntn' => $company['company_ntn'],
                    'origin' => $company['origin'],
                ];
            }
            $stakeholderData['stakeholder_as'] = $stakeholder_as;
            $stakeholderData['site_id'] = decryptParams($site_id);

            // residential address fields
            $residential = $inputs['residential'];
            $stakeholderData['residential_address_type'] = $residential['address_type'];
            $stakeholderData['residential_address'] = $residential['address'];
            $stakeholderData['residential_postal_code'] = $residential['postal_code'];
            $stakeholderData['residential_country_id'] = $residential['country'] > 0 ? $residential['country'] : 167;
            $stakeholderData['residential_state_id'] =  $residential['state'];
            $stakeholderData['residential_city_id'] =  $residential['city'];

            //mailing address fields
            $mailing = $inputs['mailing'];
            $stakeholderData['mailing_address_type'] = $mailing['address_type'];
            $stakeholderData['mailing_address'] = $mailing['address'];
            $stakeholderData['mailing_postal_code'] = $mailing['postal_code'];
            $stakeholderData['mailing_country_id'] = $mailing['country'] > 0 ? $mailing['country'] : 167;
            $stakeholderData['mailing_state_id'] = $mailing['state'];
            $stakeholderData['mailing_city_id'] = $mailing['city'];

            $stakeholderData['comments'] = $inputs['comments'];

            $stakeholder = $this->stakeholderInterface->model()->updateOrCreate([
                'id' => $stakeholderInput['stackholder_id'],
            ], $stakeholderData);

            if ($stakeholderInput['stackholder_id'] == 0) {
                $stakeholderTypeCode = Str::of($stakeholder->id)->padLeft(3, '0');
                $stakeholderTypeData  = [
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::CUSTOMER->value,
                        'stakeholder_code' => StakeholderTypeEnum::CUSTOMER->value . '-' . $stakeholderTypeCode,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::VENDOR->value,
                        'stakeholder_code' => StakeholderTypeEnum::VENDOR->value . '-' . $stakeholderTypeCode,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::DEALER->value,
                        'stakeholder_code' => StakeholderTypeEnum::DEALER->value . '-' . $stakeholderTypeCode,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::NEXT_OF_KIN->value,
                        'stakeholder_code' => StakeholderTypeEnum::NEXT_OF_KIN->value . '-' . $stakeholderTypeCode,
                        'status' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'stakeholder_id' => $stakeholder->id,
                        'type' => StakeholderTypeEnum::LEAD->value,
                        'stakeholder_code' => StakeholderTypeEnum::LEAD->value . '-' . $stakeholderTypeCode,
                        'status' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ];
                $stakeholderType = (new StakeholderType())->insert($stakeholderTypeData);
            }


            $file = FileManagement::find($inputs['file_id']);
            $serail_no = $this->model()::max('id') + 1;
            $serail_no =  sprintf('%03d', $serail_no);
            $data = [
                'site_id' => decryptParams($site_id),
                'file_id' => $inputs['file_id'],
                'unit_id' => $inputs['unit_id'],
                'stakeholder_id' => $inputs['customer_id'],
                'transfer_person_id' => $stakeholder->id,
                'sales_plan_id' => $file->sales_plan_id,
                'transfer_person_data' => json_encode(Stakeholder::find($stakeholder->id)),
                'unit_data' => json_encode(Unit::find($inputs['unit_id'])),
                'stakeholder_data' => json_encode(Stakeholder::find($inputs['customer_id'])),
                'amount_to_be_paid' => str_replace(',', '', $inputs['amount_to_be_paid']),
                'payment_due_date' => $inputs['payment_due_date'],
                'amount_remarks' => $inputs['amount_remarks'],
                'transfer_rate' => str_replace(',', '', $inputs['transfer_rate']),
                'status' => 0,
                'comments' => $inputs['comments'],
                'serial_no'=>'FTT-'.$serail_no,
            ];

            if (isset($inputs['stackholder']['next_of_kin'])) {
                $data['kin_data'] = json_encode($inputs['stackholder']['next_of_kin']);
            }

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
                changeImageDirectoryPermission();
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
