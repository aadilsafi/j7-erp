<?php

namespace App\Imports;


use App\Models\TempKins;
use App\Models\TempStakeholderContact;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;


class StakeholderConatctsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable, RemembersRowNumber;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }

    public function model(array $row)
    {
        return new TempStakeholderContact(
            [
                'stakeholder_cnic' => $row['stakeholder_cnic'],
                'full_name' => $row['full_name'],
                'father_name' => $row['father_name'],
                'cnic' => $row['cnic'],
                'designation' =>    $row['designation'],
                'contact_no' => $row['contact_no'],
                'occupation' => $row['occupation'],
                'address' => $row['address'],
                'ntn' => $row['ntn'],
            ]
        );
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }


    public function rules(): array
    {
        return [
            'stakeholder_cnic' => ['required', 'exists:App\Models\Stakeholder,cnic'],
            'full_name' => ['required'],
            'father_name' => ['sometimes'],
            'cnic' => ['required', 'unique:App\Models\StakeholderContact,cnic', 'distinct'],
        ];
    }
    public function customValidationMessages()
    {
        return [
            'identity_number.exists' => 'Parent Stakeholder CNIC / Registration No not Exists.',
            'kin_cnic.exists' => 'Kin CNIC not Exists.',
        ];
    }
}
