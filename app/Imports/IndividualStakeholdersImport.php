<?php

namespace App\Imports;

use App\Models\BacklistedStakeholder;
use App\Models\BlacklistedStakeholder;
use App\Models\TempFloor;
use App\Models\TempStakeholder;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Validators\Failure;

class IndividualStakeholdersImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable, RemembersRowNumber;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }

    public function model(array $row)
    {

        $blacklisted = BacklistedStakeholder::where('cnic', $row['cnic'])
            ->first();
        if ($blacklisted) {
            $error = ['This CNIC is Blacklisted'];
            $failures[] = new Failure($this->getRowNumber(), 'cnic', $error, $row);

            throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
        }
        return new TempStakeholder(
            [
                'full_name'  => $row['full_name'],
                'father_name'  => $row['father_name'],
                'cnic'  => $row['cnic'],
                'passport_no'  => $row['passport_no'],
                'ntn'  => $row['ntn'],
                'occupation'  => $row['occupation'],
                'designation'  => $row['designation'],
                'is_local'  => $row['is_local'],
                'nationality'  => $row['nationality'],
                'date_of_birth'  => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['date_of_birth']))->format('Y-m-d'),
                'email'  => $row['email'],
                'office_email'  => $row['office_email'],
                'mobile_contact'  => $row['mobile_contact'],
                'office_contact'  => $row['office_contact'],
                'referred_by'  => $row['referred_by'],
                'source'  => $row['lead_source'],
                'residential_address'  => $row['residential_address'],
                'residential_address_type'  => $row['residential_address_type'],
                'residential_country'  => $row['residential_country'],
                'residential_state'  => $row['residential_state'],
                'residential_city'  => $row['residential_city'],

                'residential_postal_code'  => $row['residential_postal_code'],

                'same_address_for_mailing'  => $row['same_address_for_mailing'],

                'mailing_address'  => $row['same_address_for_mailing'] == 'yes' ? $row['residential_address'] : $row['mailing_address'],
                'mailing_address_type'  => $row['same_address_for_mailing'] == 'yes' ? $row['residential_address_type'] : $row['mailing_address_type'],
                'mailing_country'  => $row['same_address_for_mailing'] == 'yes' ? $row['residential_country'] : $row['mailing_country'],
                'mailing_state'  => $row['same_address_for_mailing'] == 'yes' ? $row['residential_state'] : $row['mailing_state'],
                'mailing_city'  => $row['same_address_for_mailing'] == 'yes' ? $row['residential_city'] : $row['mailing_city'],
                'mailing_postal_code'  => $row['same_address_for_mailing'] == 'yes' ? $row['residential_postal_code'] : $row['mailing_postal_code'],

                'comments'  => $row['comments'],
                'is_dealer'  => $row['is_dealer'],
                'is_vendor'  => $row['is_vendor'],
                'is_customer'  => $row['is_customer'],
                'is_kin'  => $row['is_kin'],
            ]
        );
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    public function batchSize(): int
    {
        return 5000;
    }


    public function rules(): array
    {
        return [
            'full_name' => ['required'],
            'father_name' => ['required'],
            'cnic' => ['required', 'unique:App\Models\Stakeholder,cnic', 'distinct'],
            'passport_no' => ['sometimes', 'nullable', 'unique:App\Models\Stakeholder,passport_no', 'distinct'],
            'ntn' => ['sometimes', 'nullable','unique:App\Models\Stakeholder,ntn', 'distinct'],
            'occupation' => ['required'],
            'designation' => ['required'],
            'is_local'  => ['required'],
            'nationality' => ['required'],
            'date_of_birth' => ['required'],
            'email' => ['sometimes', 'nullable', 'email:rfc,dns', 'distinct'],
            'office_email' => ['sometimes', 'nullable', 'email:rfc,dns', 'distinct'],
            'mobile_contact' => ['required'],
            'office_contact' => ['sometimes', 'nullable'],
            'refered_by' => ['sometimes', 'nullable'],
            'lead_source' => ['sometimes', 'nullable'],
            'residential_address' => ['required'],
            'residential_address_type' => ['required'],
            'residential_country' => ['required'],
            'residential_state' => ['required'],
            'residential_city' => ['required'],
            'residential_postal_code' => ['required'],
            'residential_address' => ['required'],
            'same_address_for_mailing' => ['required', 'in:yes,no'],
            'mailing_address' => ['required_if:same_address_for_mailing,no'],
            'mailing_address_type' => ['required_if:same_address_for_mailing,no'],
            'mailing_country' => ['required_if:same_address_for_mailing,no'],
            'mailing_state' => ['required_if:same_address_for_mailing,no'],
            'mailing_city' => ['required_if:same_address_for_mailing,no'],
            'mailing_postal_code' => ['required_if:same_address_for_mailing,no'],
            'comments' => ['sometimes', 'nullable'],
            'is_dealer' => ['required', 'in:yes,no'],
            'is_vendor' => ['required', 'in:yes,no'],
            'is_customer' => ['required', 'in:yes,no'],
            'is_kin' => ['required', 'in:yes,no'],
        ];
    }
}
