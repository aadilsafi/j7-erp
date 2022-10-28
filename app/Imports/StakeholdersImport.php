<?php

namespace App\Imports;

use App\Models\TempFloor;
use App\Models\TempStakeholder;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StakeholdersImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }

    public function model(array $row)
    {
        return new TempStakeholder(
            [
                'full_name' => $row['full_name'],
                'father_name' => $row['father_name'],
                'occupation' => $row['occupation'],
                'designation' => $row['designation'],
                'cnic' => (string)$row['cnic'],
                'ntn' => $row['ntn'],
                'contact' => $row['contact'],
                'address' => $row['address'],
                'comments' => $row['comments'],
                'is_dealer' => $row['is_dealer'],
                'is_vendor' => $row['is_vendor'],
                'is_customer' => $row['is_customer'],
                'is_kin' => $row['is_kin'],
                'parent_cnic' => $row['parent_cnic'],
                'relation' => $row['relation'],
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
            'ntn' => ['required', 'unique:App\Models\Stakeholder,ntn', 'distinct'],
            'contact' => ['required'],
            'address' => ['required'],
            'is_dealer' => ['required', 'in:yes,no'],
            'is_vendor' => ['required', 'in:yes,no'],
            'is_customer' => ['required', 'in:yes,no'],
            'is_kin' => ['required', 'in:yes,no'],
            'parent_cnic' => ['sometimes'],
            'relation' => ['sometimes'],
        ];
    }
    // public function rules(): array
    // {
    //     return [
    //         '0' => ['required', 'numeric', 'max:255'],
    //         '1' => ['required', 'numeric'],
    //         '2' => ['required', 'string', 'max:10'],
    //     ];
    // }
}
