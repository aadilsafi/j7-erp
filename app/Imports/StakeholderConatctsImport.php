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
                'stakeholder_cnic' => $row['identity_number'],
                'full_name' => $row['full_name'],
                'father_name' => $row['father_name'],
                'cnic' => $row['cnic'],
                
            ]
        );
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function batchSize(): int
    {
        return 50;
    }


    public function rules(): array
    {
        return [
            'identity_number' => ['required', 'exists:App\Models\Stakeholder,cnic'],
            'kin_cnic' => ['required', 'exists:App\Models\Stakeholder,cnic'],
            'relation' => ['required'],
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
