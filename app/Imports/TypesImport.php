<?php

namespace App\Imports;

use App\Models\TempFloor;
use App\Models\TempUnitType;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TypesImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }


    public function model(array $row)
    {
        return new TempUnitType([
            $this->selectedFields[0] => $row['name'],
            $this->selectedFields[1] => $row['unit_type_slug'],
            $this->selectedFields[2] => $row['parent_type_name'],
        ]);
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
            'unit_type_slug' =>  ['required', 'unique:App\Models\Type,slug', 'distinct'],
            'name' =>  ['required'],
            'parent_type_name' =>  ['sometimes'],
        ];
    }
}
