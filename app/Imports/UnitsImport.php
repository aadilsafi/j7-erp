<?php

namespace App\Imports;

use App\Models\AdditionalCost;
use App\Models\TempAdditionalCost;
use App\Models\TempUnit;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Validators\Failure;

class UnitsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable, RemembersRowNumber;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }


    public function model(array $row)
    {
        if (strtolower($row['additional_costs_name']) != 'null' && strtolower($row['additional_costs_name']) != '' ) {
            $adCosts = AdditionalCost::where('slug', $row['additional_costs_name'])
                ->first();
            if (!$adCosts) {
                $error = ['Additional costs does not Exists.'];
                $failures[] = new Failure($this->getRowNumber(), 'additional_costs_name', $error, $row);

                throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
            }
        }


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
            'status' => 'Open',
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
            'floor_short_label' =>  ['required', 'exists:App\Models\Floor,short_label'],
            'name' =>  ['required'],
            'unit_short_label' =>  ['required', 'unique:App\Models\Unit,floor_unit_number', 'distinct'],
            'net_area' =>  ['required', 'numeric', 'gt:0'],
            'gross_area' =>  ['required', 'numeric', 'gte:*.net_area'],
            'price_sqft' =>  ['required', 'gt:0'],
            'unit_type_slug' =>  ['required', 'exists:App\Models\Type,slug'],
            'parent_unit_short_label' =>  ['required'],
            'is_corner' =>  ['required'],
            'is_facing' =>  ['required'],
            // 'additional_costs_name' =>  ['sometimes','nullable','exists:App\Models\AdditionalCost,slug'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'floor_short_label.exists' => 'Floor dose not Exists.',
            'additional_costs_name.exists' => 'Additional costs does not Exists.',
            'gross_area.gte' =>  'Gross Area Must be greater then Net area',
            'unit_type_slug' => 'Unit Type is not Exists.'
        ];
    }
}
