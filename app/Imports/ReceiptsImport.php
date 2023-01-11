<?php

namespace App\Imports;

use App\Models\SalesPlan;
use App\Models\Stakeholder;
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

class ReceiptsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
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
            ->first();
        if (!$salePlan) {
            $error = ['Could not find sales Plan'];
            $failures[] = new Failure($this->getRowNumber(), 'unit_short_label', $error, $row);

            throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
        }

        return new TempReceipt([
            'doc_no' => $row['doc_no'],
            'unit_short_label' => $row['unit_short_label'],
            'stakeholder_cnic' => $row['stakeholder_cnic'],
            'total_price' => $row['total_price'],
            'down_payment_total' => $row['down_payment_total'],
            'validity' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['sales_plan_approval_date']))->format('Y-m-d 00:00:00'),
            'mode_of_payment' => $row['mode_of_payment'],
            'amount' => $row['amount'],
            'cheque_no' => $row['cheque_no'],
            'bank_acount_number' => $row['bank_acount_number'],
            'online_transaction_no' => $row['online_transaction_no'],
            'transaction_date' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['transaction_date']))->format('Y-m-d 00:00:00'),
            'creation_date' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['creation_date']))->format('Y-m-d 00:00:00'),
            'status' => $row['status'],
            'image_url' => $row['image_url'],
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
            'doc_no' =>  ['required', 'unique:receipts,doc_no','distinct'],
            'unit_short_label' =>  ['required', 'exists:App\Models\Unit,floor_unit_number'],
            'stakeholder_cnic' =>  ['required', 'exists:App\Models\Stakeholder,cnic'],
            'total_price' =>  ['required', 'numeric', 'gt:0'],
            'down_payment_total' =>  ['required', 'numeric', 'gt:0'],
            'sales_plan_approval_date' =>  ['required'],
            'bank_acount_number' => ['sometimes', 'nullable', 'exists:banks,account_number'],
            'mode_of_payment' =>  ['required'],
            'amount' => ['required'],
            'status' => ['required'],
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
