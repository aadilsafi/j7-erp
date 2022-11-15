<?php

namespace App\Imports;

use App\Models\BacklistedStakeholder;
use App\Models\BlacklistedStakeholder;
use App\Models\TempFloor;
use App\Models\TempStakeholder;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Validators\Failure;

class StakeholdersImport implements ToModel, WithChunkReading, WithBatchInserts, WithHeadingRow, WithValidation
{
    use Importable, RemembersRowNumber;

    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields = $selectedFields;
    }

    public function model(array $row)
    {

        $blacklisted = BacklistedStakeholder::where('cnic', $row['cnic'])
            ->first();
        if ($blacklisted) {
            $error = ['This CNIC is Blacklisted'];
            $failures[] = new Failure($this->getRowNumber(), 'cnic', $error, $row);

            throw new \Maatwebsite\Excel\Validators\ValidationException(\Illuminate\Validation\ValidationException::withMessages($error), $failures);
        }
        return new TempStakeholder(
            [
                'full_name' => $row['full_name'],
                'father_name' => $row['father_name'],
                'occupation' => $row['occupation'],
                'designation' => $row['designation'],
                'cnic' => (string)$row['cnic'],
                'ntn' => $row['ntn'],
                'contact' => $row['contact'],
                'address' => $row['address'],
                'comments' => $row['comments'],
                'is_dealer' => $row['is_dealer'],
                'is_vendor' => $row['is_vendor'],
                'is_customer' => $row['is_customer'],
                'country' => $row['country'],
                'state' => $row['state'],
                'city' => $row['city'],
                'optional_contact_number' => json_encode($row['optional_contact_number']),
                'nationality' => $row['nationality'],
            ]
        );
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
            'full_name' => ['required'],
            'father_name' => ['required'],
            'cnic' => ['required', 'unique:App\Models\Stakeholder,cnic', 'distinct'],
            'ntn' => ['unique:App\Models\Stakeholder,ntn', 'distinct'],
            'contact' => ['required'],
            'address' => ['required'],
            'is_dealer' => ['required', 'in:yes,no'],
            'is_vendor' => ['required', 'in:yes,no'],
            'is_customer' => ['required', 'in:yes,no'],
            // 'is_kin' => ['required', 'in:yes,no'],
            // 'parent_cnic' => ['sometimes'],
            // 'relation' => ['sometimes'],
            'country' => ['required'],
        ];
    }
    // public function rules(): array
    // {
    //     return [
    //         '0' => ['required', 'numeric', 'max:255'],
    //         '1' => ['required', 'numeric'],
    //         '2' => ['required', 'string', 'max:10'],
    //     ];
    // }
}
