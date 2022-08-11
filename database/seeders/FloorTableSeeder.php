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
                'name' => 'Ground Floor',
                'width' => 1300,
                'length' => 1300,
                'site_id' => 1,
                'order' => 0,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Floor 1',
                'width' => 1300,
                'length' => 1300,
                'site_id' => 1,
                'order' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Floor 2',
                'width' => 1300,
                'length' => 1300,
                'site_id' => 1,
                'order' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Floor 3',
                'width' => 1300,
                'length' => 1300,
                'site_id' => 1,
                'order' => 3,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        (new Floor())->insert($data);
    }
}
