<?php

namespace App\Imports;

use App\Models\TempFloor;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FloorImport implements ToModel, WithChunkReading, ShouldQueue, WithBatchInserts, WithHeadingRow
{
    private $selectedFields;

    public function  __construct($selectedFields)
    {
        $this->selectedFields= $selectedFields;
    }


    use Importable;
    public function model(array $row)
    {
        return new TempFloor([
            $this->selectedFields[0] => $row['name'],
            $this->selectedFields[1] => $row['floor_area'],
            $this->selectedFields[2] => $row['short_label'],
        ]);
    }

    public function chunkSize(): int
    {
        return 200;
    }

    public function batchSize(): int
    {
        return 200;
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
