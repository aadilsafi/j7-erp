<?php

namespace App\Imports;

use App\Models\TempBank;
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

class BanksImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }

    public function model(array $row)
    {
        return new TempBank(
            [
                'name' => $row['name'],
                'account_number' => $row['account_number'],
                'branch_code' => $row['branch_code'],
                'address' => $row['address'],
                'contact_number' => $row['contact_number'],
                'comments' => $row['comments']
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
            'name' => ['required'],
            'account_number' => ['required', 'unique:App\Models\Bank,account_number', 'distinct'],
            'branch_code' => ['required', 'unique:App\Models\Bank,branch_code', 'distinct'],
            'contact_number' => ['required'],
            'address' => ['required'],
        ];
    }
}
