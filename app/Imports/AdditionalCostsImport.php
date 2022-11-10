<?php

namespace App\Imports;

use App\Models\TempAdditionalCost;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AdditionalCostsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }


    public function model(array $row)
    {
        return new TempAdditionalCost([
            'additional_costs_name' => $row['additional_costs_name'],
            'site_percentage' => $row['site_percentage'],
            'floor_percentage' => $row['floor_percentage'],
            'unit_percentage' => $row['unit_percentage'],
            'is_sub_types' => $row['is_sub_types'],
            'parent_type_name' => $row['parent_type_name'],

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
        //,NULL,id,deleted_at,NULL
        return [
            'additional_costs_name' =>  ['required', 'unique:App\Models\AdditionalCost,slug', 'distinct'],
            'site_percentage' =>  ['required'],
            'floor_percentage' =>  ['required'],
            'unit_percentage' =>  ['required'],
            'is_sub_types' =>  ['required'],
            'parent_type_name' =>  ['sometimes'],

        ];
    }
}
