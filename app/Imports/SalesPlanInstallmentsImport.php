<?php

namespace App\Imports;

use App\Models\SalesPlan;
use App\Models\Stakeholder;
use App\Models\TempSalePlanInstallment;
use App\Models\Unit;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;

class SalesPlanInstallmentsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable, RemembersRowNumber;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }


    public function model(array $row)
    {
        $stakeholderId = Stakeholder::select('id')->where('cnic', $row['stakeholder_cnic'])->first();
        $unitId = Unit::select('id')->where('floor_unit_number', $row['unit_short_label'])->first();

        $salePlan = SalesPlan::where('stakeholder_id', $stakeholderId->id)
            ->where('unit_id', $unitId->id)
            ->where('total_price', $row['total_price'])
            ->where('down_payment_total', $row['down_payment_total'])
            ->where('validity', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['validity']))->format('Y-m-d'))
            ->first();
        if (!$salePlan) {
            $error = ['Could not find sales Plan'];
            $failures[] = new Failure($this->getRowNumber(), 'unit_short_label', $error, $row);

            throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
        }

        return new TempSalePlanInstallment([
            'unit_short_label' => $row['unit_short_label'],
            'stakeholder_cnic' => $row['stakeholder_cnic'],
            'total_price' => $row['total_price'],
            'down_payment_total' => $row['down_payment_total'],
            'validity' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['validity']))->format('Y-m-d'),
            'type' => $row['type'],
            'label' => $row['label'],
            'due_date' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['due_date']))->format('Y-m-d'),
            'installment_no' => $row['installment_no'],
            'total_amount' => $row['total_amount'],
            // 'paid_amount' => $row['paid_amount'],
            // 'remaining_amount' => $row['remaining_amount'],
            // 'last_paid_at' => (!is_null($row['last_paid_at']) ? Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['last_paid_at']))->format('Y-m-d') : null),
            // 'status' => $row['status'],
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
            'due_date' => ['required'],
            'installment_no' =>  ['required'],
            'total_amount' =>  ['required', 'numeric', 'gt:0'],
            // 'paid_amount' =>  ['required', 'numeric'],
            // 'remaining_amount' =>  ['required', 'numeric'],
            // 'status' => ['required']
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
