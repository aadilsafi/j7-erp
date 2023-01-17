<?php

namespace App\Imports;

use App\Models\FileManagement;
use App\Models\SalesPlan;
use App\Models\Stakeholder;
use App\Models\StakeholderNextOfKin;
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
use Str;

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
        if (Str::length($row['kin_cnic']) > 0) {
            $file = FileManagement::where('doc_no', $row['file_doc_no'])->first();
            $stakeholder_id = $file->stakeholder_id;
            $kin = Stakeholder::where('cnic', $row['kin_cnic'])->first()->id;

            $kinExists = StakeholderNextOfKin::where('stakeholder_id', $stakeholder_id)->where('kin_id', $kin)->exists();
            if (!$kinExists) {
                $error = ['Could not find Stakeholder Next of Kin'];
                $failures[] = new Failure($this->getRowNumber(), 'kin_cnic', $error, $row);
                throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
            }
        }


        // $stakeholderId = Stakeholder::select('id')->where('cnic', $row['stakeholder_cnic'])->first();
        // $unitId = Unit::select('id')->where('floor_unit_number', $row['unit_short_label'])->first();

        // $salePlan = SalesPlan::where('stakeholder_id', $stakeholderId->id)
        //     ->where('unit_id', $unitId->id)
        //     ->where('total_price', $row['total_price'])
        //     ->where('down_payment_total', $row['down_payment_total'])
        //     ->where('approved_date', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['sales_plan_approval_date']))->format('Y-m-d 00:00:00'))
        //     ->where('status', 1)
        //     ->first();
        // if (!$salePlan) {
        //     $error = ['Could not find sales Plan'];
        //     $failures[] = new Failure($this->getRowNumber(), 'unit_short_label', $error, $row);

        //     throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
        // }

        return new TempFilesStakeholderContact([
            'file_doc_no' => $row['file_doc_no'],
            'contact_cnic' => $row['contact_cnic'],
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
            'file_doc_no' => ['required', 'exists:file_management,doc_no'],
            'contact_cnic' => ['sometimes', 'nullable', 'exists:App\Models\StakeholderContact,cnic'],
            'kin_cnic' => ['sometimes', 'nullable', 'exists:App\Models\Stakeholder,cnic'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'file_doc_no.exists' => 'File dose not Exists.',
            'contact_cnic.exists' => 'Stakeholder Contact does not Exists.',
            'kin_cnic.exists' => 'Kin Doses not Exists.'
        ];
    }
}
