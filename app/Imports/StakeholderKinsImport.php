<?php

namespace App\Imports;

use App\Models\BacklistedStakeholder;
use App\Models\BlacklistedStakeholder;
use App\Models\TempFloor;
use App\Models\TempKins;
use App\Models\TempStakeholder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Validators\Failure;

class StakeholderKinsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable, RemembersRowNumber;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }

    public function model(array $row)
    {
        return new TempKins(
            [
                'stakeholder_cnic' => $row['stakeholder_cnic'],
                'kin_cnic' => $row['kin_cnic'],
                'relation' => $row['relation'],
            ]
        );
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function batchSize(): int
    {
        return 100;
    }


    public function rules(): array
    {
        return [
            'stakeholder_cnic' => ['required', 'exists:App\Models\Stakeholder,cnic'],
            'kin_cnic' => ['required', 'exists:App\Models\Stakeholder,cnic'],
            'relation' => ['required'],
        ];
    }
    public function customValidationMessages()
    {
        return [
            'stakeholder_cnic.exists' => 'Parent Stakeholder CNIC not Exists.',
            'kin_cnic.exists' => 'Parent Stakeholder CNIC not Exists.',
        ];
    }
}
