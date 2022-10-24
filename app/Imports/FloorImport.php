<?php

namespace App\Imports;

use App\Models\TempFloor;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\Importable;

class FloorImport implements ToModel, WithChunkReading, ShouldQueue
{
    use Importable;
    public function model(array $row)
    {

        return new TempFloor([
            'name'     => $row[0],
            'floor_area'    => $row[1],
            'short_label' => $row[2],
        ]);
    }

    public function chunkSize(): int
    {
        return 100;
    }

}
