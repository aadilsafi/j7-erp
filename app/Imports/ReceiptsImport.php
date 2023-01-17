<?php

namespace App\Imports;

use App\Models\SalesPlan;
use App\Models\Stakeholder;
use App\Models\TempReceipt;
use App\Models\TempSalePlanInstallment;
use App\Models\Unit;
use Carbon\Carbon;
use File;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Str;

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
        if (strtolower($row['mode_of_payment']) == 'cheque') {
            if (Str::length($row['cheque_no']) == 0) {
                $error = ['Cheque No is required in Case of Cheque.'];
                $failures[] = new Failure($this->getRowNumber(), 'cheque_no', $error, $row);

                throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
            }

            if (Str::length($row['bank_acount_number']) == 0) {
                $error = ['Bank Account Number is required in Case of Cheque.'];
                $failures[] = new Failure($this->getRowNumber(), 'bank_acount_number', $error, $row);

                throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
            }
        }

        if (strtolower($row['mode_of_payment']) == 'online') {
            if (Str::length($row['online_transaction_no']) == 0) {
                $error = ['Online Transaction No is required in Case of Online.'];
                $failures[] = new Failure($this->getRowNumber(), 'online_transaction_no', $error, $row);

                throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
            }

            if (Str::length($row['bank_acount_number']) == 0) {
                $error = ['Bank Account Number is required in Case of Online.'];
                $failures[] = new Failure($this->getRowNumber(), 'bank_acount_number', $error, $row);

                throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
            }
        }

        if (Str::length($row['image_url']) > 0) {
            $isFileExists = File::exists(public_path('app-assets/images/Import/' . $row['image_url']));
            if (!$isFileExists) {
                $error = ['Image does not exists'];
                $failures[] = new Failure($this->getRowNumber(), 'image_url', $error, $row);

                throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
            }
        } else {
            if (strtolower($row['mode_of_payment']) != 'cash') {
                $error = ['Image is required'];
                $failures[] = new Failure($this->getRowNumber(), 'image_url', $error, $row);

                throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
            }
        }

        return new TempReceipt([
            'doc_no' => $row['doc_no'],
            'sales_plan_doc_no' => $row['sales_plan_doc_no'],
            'mode_of_payment' => $row['mode_of_payment'],
            'amount' => $row['amount'],
            'cheque_no' => $row['cheque_no'],
            'bank_acount_number' => $row['bank_acount_number'],
            'online_transaction_no' => $row['online_transaction_no'],
            'transaction_date' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['transaction_date']))->format('Y-m-d 00:00:00'),
            'creation_date' => Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['creation_date']))->format('Y-m-d 00:00:00'),
            'status' => $row['status'],
            'image_url' => $row['image_url'],
            'discounted_amount' => $row['discounted_amount'],
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
            'doc_no' =>  ['required', 'unique:receipts,doc_no', 'distinct'],
            'sales_plan_doc_no' => ['required', 'exists:sales_plans,doc_no'],
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
