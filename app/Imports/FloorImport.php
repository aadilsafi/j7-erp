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

class FloorImport implements ToModel, WithChunkReading, ShouldQueue, WithBatchInserts
{
    use Importable;
    public function model(array $row)
    {
        return new TempFloor([
            'name' => $row[0],
            'floor_area' => $row[1],
            'short_label' => $row[2],
        ]);
    }

    public function chunkSize(): int
    {
        return 5000;
    }

    public function batchSize(): int
    {
        return 5000;
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
