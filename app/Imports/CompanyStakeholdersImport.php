<?php

namespace App\Imports;

use App\Models\BacklistedStakeholder;
use App\Models\BlacklistedStakeholder;
use App\Models\TempCompanyStakeholder;
use App\Models\TempFloor;
use App\Models\TempStakeholder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Validators\Failure;

class CompanyStakeholdersImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable, RemembersRowNumber;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }

    public function model(array $row)
    {

        return new TempCompanyStakeholder(
            [
                'company_name' => $row['company_name'],
                'registration' => $row['registration'],
                'strn' => $row['strn'],
                'ntn ' => $row['ntn'],
                'origin' => $row['origin'],
                'email' => $row['email'],
                'office_email' => $row['office_email'],
                'mobile_contact' => $row['mobile_contact'],
                'office_contact' => $row['office_contact'],
                'website' => $row['website'],
                'parent_company' => $row['parent_company'],
                'residential_address' => $row['billing_address'],
                'residential_address_type' => $row['billing_address_type'],
                'residential_country' => $row['billing_country'],
                'residential_state' => $row['billing_state'],
                'residential_city' => $row['billing_city'],
                'residential_postal_code' => $row['billing_postal_code'],
                'same_address_for_mailing' => $row['same_address_for_shipping'],
                'mailing_address' => $row['shipping_address'],
                'mailing_address_type' => $row['shipping_address_type'],
                'mailing_country' => $row['shipping_country'],
                'mailing_state' => $row['shipping_state'],
                'mailing_city' => $row['shipping_city'],
                'mailing_postal_code' => $row['shipping_postal_code'],
                'comments' => $row['comments'],
                'is_dealer' => $row['is_dealer'],
                'is_vendor' => $row['is_vendor'],
                'is_customer' => $row['is_customer'],
                'is_kin' => $row['is_kin'],

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
            'company_name' => ['required'],
            'registration' => ['required'],
            'industry' => ['required'],
            'strn' => ['required'],
            'ntn' => ['required'],
            'origin' => ['required'],
            'email' => ['sometimes', 'nullable', 'email:rfc,dns', 'distinct'],
            'office_email' => ['sometimes', 'nullable', 'email:rfc,dns', 'distinct'],
            'mobile_contact' => ['required'],
            'office_contact' => ['sometimes', 'nullable'],
            'website' => ['sometimes'],
            'parent_company' => ['sometimes'],
            'billing_address' => ['required'],
            'billing_address_type' => ['required'],
            'billing_country' => ['required'],
            'billing_state' => ['required'],
            'billing_city' => ['required'],
            'billing_postal_code' => ['required'],
            'billing_address' => ['required'],
            'same_address_for_shipping' => ['required', 'in:yes,no'],
            'shipping_address' => ['required_if:same_address_for_shipping,no'],
            'shipping_address_type' => ['required_if:same_address_for_shipping,no'],
            'shipping_country' => ['required_if:same_address_for_shipping,no'],
            'shipping_state' => ['required_if:same_address_for_shipping,no'],
            'shipping_city' => ['required_if:same_address_for_shipping,no'],
            'shipping_postal_code' => ['required_if:same_address_for_shipping,no'],
            'comments' => ['sometimes', 'nullable'],
            'is_dealer' => ['required', 'in:yes,no'],
            'is_vendor' => ['required', 'in:yes,no'],
            'is_customer' => ['required', 'in:yes,no'],
            'is_kin' => ['required', 'in:yes,no'],
        ];
    }
}
