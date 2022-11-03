<?php

namespace App\Imports;

use App\Models\TempSalePlan;
use App\Models\TempSalePlanInstallment;
use App\Models\TempUnit;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class SalesPlanInstallmentsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }


    public function model(array $row)
    {
        return new TempSalePlanInstallment([
            'unit_short_label' => $row['unit_short_label'],
            'stakeholder_cnic' => $row['stakeholder_cnic'],
            'total_price' => $row['total_price'],
            'down_payment_total' => $row['down_payment_total'],
            'validity' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['validity']))->format('Y-m-d'),
            'type' => $row['type'],
            'installment_no' => $row['installment_no'],
            'total_amount' => $row['total_amount'],
            'paid_amount' => $row['paid_amount'],
            'remaining_amount' => $row['remaining_amount'],
            'remarks' => $row['remarks'],
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
            'type' =>  ['required'],
            'installment_no' =>  ['required'],
            'total_amount' =>  ['required', 'numeric', 'gt:0'],
            'paid_amount' =>  ['required', 'numeric'],
            'remaining_amount' =>  ['required', 'numeric'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'unit_short_label.exists' => 'Unit dose not Exists.',
            'stakeholder_cnic.exists' => 'Stakeholder does not Exists.',
        ];
    }
}
