<?php

namespace App\Imports;

use App\Models\SalesPlan;
use App\Models\Stakeholder;
use App\Models\TempFiles;
use App\Models\TempFilesStakeholderContact;
use App\Models\TempReceipt;
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

class FilesConatctsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
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
            ->where('approved_date', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['sales_plan_approval_date']))->format('Y-m-d 00:00:00'))
            ->where('status', 1)
            ->first();
        if (!$salePlan) {
            $error = ['Could not find sales Plan'];
            $failures[] = new Failure($this->getRowNumber(), 'unit_short_label', $error, $row);

            throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
        }

        return new TempFilesStakeholderContact([
            'unit_short_label' => $row['unit_short_label'],
            'stakeholder_cnic' => $row['stakeholder_cnic'],
            'total_price' => $row['total_price'],
            'down_payment_total' => $row['down_payment_total'],
            'sales_plan_approval_date' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['sales_plan_approval_date']))->format('Y-m-d 00:00:00'),
            'conatct_cnic' => $row['conatct_cnic'],
            'kin_cnic' => $row['kin_cnic'],
        ]);
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function batchSize(): int
    {
        return 50;
    }


    public function rules(): array
    {
        return [
            'unit_short_label' =>  ['required', 'exists:App\Models\Unit,floor_unit_number'],
            'stakeholder_cnic' =>  ['required', 'exists:App\Models\Stakeholder,cnic'],
            'total_price' =>  ['required', 'numeric', 'gt:0'],
            'down_payment_total' =>  ['required', 'numeric', 'gt:0'],
            'sales_plan_approval_date' =>  ['required'],
            'conatct_cnic' => ['sometimes', 'nullable', 'exists:App\Models\StakeholderContact,cnic'],
            'kin_cnic' => ['sometimes', 'nullable', 'exists:App\Models\Stakeholder,cnic'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'unit_short_label.exists' => 'Unit dose not Exists.',
            'stakeholder_cnic.exists' => 'Stakeholder does not Exists.',
            'bank_acount_number.exists' => 'Bank Account Doses not Exists.'
        ];
    }
}
