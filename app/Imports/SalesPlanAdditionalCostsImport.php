<?php

namespace App\Imports;

use App\Models\SalesPlan;
use App\Models\Stakeholder;
use App\Models\TempSalesPlanAdditionalCost;
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

class SalesPlanAdditionalCostsImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable, RemembersRowNumber;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }


    public function model(array $row)
    {
        // $stakeholderId = Stakeholder::select('id')->where('cnic', $row['stakeholder_cnic'])->first();
        // $unitId = Unit::select('id')->where('floor_unit_number', $row['unit_short_label'])->first();

        // $salePlan = SalesPlan::where('stakeholder_id', $stakeholderId->id)
        //     ->where('unit_id', $unitId->id)
        //     ->where('total_price', $row['total_price'])
        //     ->where('down_payment_total', $row['down_payment_total'])
        //     ->where('validity', Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['validity']))->format('Y-m-d 00:00:00'))
        //     ->first();
        // if (!$salePlan) {
        //     $error = ['Could not find sales Plan'];
        //     $failures[] = new Failure($this->getRowNumber(), 'unit_short_label', $error, $row);

        //     throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
        // }

        return new TempSalesPlanAdditionalCost([
            'sales_plan_doc_no' => $row['sales_plan_doc_no'],
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
            'sales_plan_doc_no' => ['required', 'exists:sales_plans,doc_no'],
            'additional_costs_name' =>  ['required', 'exists:App\Models\AdditionalCost,slug'],
            'percentage' =>  ['required', 'numeric'],
            'total_amount' =>  ['required', 'numeric'],
        ];
    }

    public function customValidationMessages()
    {
        return [
            'unit_short_label.exists' => 'Unit dose not Exists.',
            'stakeholder_cnic.exists' => 'Satkeholder does not Exists.',
            'additional_costs_name.exists' =>  'Please provide valid Additional Costs name',
            'unit_type_slug' => 'Unit Type is not Exists.'
        ];
    }
}
