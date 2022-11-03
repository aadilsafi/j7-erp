<?php

namespace App\Imports;

use App\Models\TempSalePlan;
use App\Models\TempSalesPlanAdditionalCost;
use App\Models\TempUnit;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalesPlanAdditionalCostsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }


    public function model(array $row)
    {

        return new TempSalesPlanAdditionalCost([
            'unit_short_label' => $row['unit_short_label'],
            'stakeholder_cnic' => $row['stakeholder_cnic'],
            'total_price' => $row['total_price'],
            'down_payment_total' => $row['down_payment_total'],
            'validity' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['validity']))->format('Y-m-d'),
            'additional_costs_name' => $row['additional_costs_name'],
            'percentage' => $row['percentage'],
            'total_amount' => $row['total_amount'],
        ]);
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
            'unit_short_label' =>  ['required', 'exists:App\Models\Unit,floor_unit_number'],
            'stakeholder_cnic' =>  ['required', 'exists:App\Models\Stakeholder,cnic'],
            'total_price' =>  ['required', 'numeric', 'gt:0'],
            'down_payment_total' =>  ['required', 'numeric', 'gt:0'],
            'validity' =>  ['required'],
            'additional_costs_name' =>  ['required', 'exists:App\Models\AdditionalCost,slug'],
            'percentage' =>  ['required', 'numeric'],
            'total_amount' =>  ['required', 'numeric'],
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
