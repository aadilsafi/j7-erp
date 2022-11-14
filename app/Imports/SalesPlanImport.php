<?php

namespace App\Imports;

use App\Models\TempSalePlan;
use App\Models\TempUnit;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalesPlanImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }


    public function model(array $row)
    {

        return new TempSalePlan([
            'unit_short_label' => $row['unit_short_label'],
            'stakeholder_cnic' => $row['stakeholder_cnic'],
            'unit_price' => $row['unit_price'],
            'total_price' => $row['total_price'],
            'discount_percentage' => $row['discount_percentage'],
            'discount_total' => $row['discount_total'],
            'down_payment_percentage' => $row['down_payment_percentage'],
            'down_payment_total' => $row['down_payment_total'],
            'lead_source' => $row['lead_source'],
            'validity' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['validity']))->format('Y-m-d'),
            'status' => 'approved',
            'comment' => $row['comment'],
            'approved_date' => strtolower($row['approved_date']) != 'null' ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['approved_date']))->format('Y-m-d') : null,
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
            'unit_price' =>  ['required', 'numeric', 'gt:0'],
            'total_price' =>  ['required', 'numeric', 'gt:0'],
            'discount_percentage' =>  ['required', 'numeric'],
            'down_payment_percentage' =>  ['required'],
            'lead_source' =>  ['required'],
            'validity' =>  ['required'],
            // 'status' =>  ['required'],
            'approved_date' =>  ['sometimes', 'nullable'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'floor_short_label.exists' => 'Floor dose not Exists.',
            'unit_short_label.exists' => 'Unit dose not Exists.',
            'additional_costs_name.exists' => 'Additional costs does not Exists.',
            'gross_area.gte' =>  'Gross Area Must be greater then Net area',
            'unit_type_slug' => 'Unit Type is not Exists.'
        ];
    }
}
