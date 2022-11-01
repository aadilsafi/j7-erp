<?php

namespace App\Imports;

use App\Models\TempAdditionalCost;
use App\Models\TempUnit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UnitsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }


    public function model(array $row)
    {
        return new TempUnit([
            'floor_short_label' => $row['floor_short_label'],
            'name' => $row['name'],
            'width' => $row['width'],
            'length' => $row['length'],
            'unit_short_label' => $row['unit_short_label'],
            'net_area' => $row['net_area'],
            'gross_area' => $row['gross_area'],
            'price_sqft' => $row['price_sqft'],
            'total_price' => $row['total_price'],
            'unit_type_slug' => $row['unit_type_slug'],
            'status' => $row['status'],
            'parent_unit_short_label' => $row['parent_unit_short_label'],
            'is_corner' => $row['is_corner'],
            'is_facing' => $row['is_facing'],
            'additional_costs_name' => $row['additional_costs_name'],
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
            'floor_short_label' =>  ['required',],
            'name' =>  ['required'],
            'unit_short_label' =>  ['required', 'unique:App\Models\Unit,floor_unit_number', 'distinct'],
            'net_area' =>  ['required'],
            'gross_area' =>  ['required'],
            'price_sqft' =>  ['required'],
            'unit_type_slug' =>  ['required'],
            'status' =>  ['required'],
            'parent_unit_short_label' =>  ['required'],
            'is_corner' =>  ['required'],
            'is_facing' =>  ['required'],
            'additional_costs_name' =>  ['sometimes','exists:App\Models\AdditionalCost,slug'],
        ];
    }
}
