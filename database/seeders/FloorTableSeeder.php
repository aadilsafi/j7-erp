<?php

namespace Database\Seeders;

use App\Models\Floor;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FloorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Floor 1',
                'width' => 1300,
                'length' => 1300,
                'site_id' => 1,
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Floor 2',
                'width' => 1300,
                'length' => 1300,
                'site_id' => 1,
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Floor 3',
                'width' => 1300,
                'length' => 1300,
                'site_id' => 1,
                'order' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        (new Floor())->insert($data);
    }
}
