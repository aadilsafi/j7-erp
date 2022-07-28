<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitTableSeeder extends Seeder
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
                'floor_id' => 1,
                'name' => 'Unit 1',
                'width' => 33,
                'length' => 33,
                'unit_number' => 1,
                'price' => 3300,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'facing_id' => null,
                'type_id' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 2',
                'width' => 33,
                'length' => 33,
                'unit_number' => 2,
                'price' => 3300,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'facing_id' => null,
                'type_id' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 3',
                'width' => 33,
                'length' => 33,
                'unit_number' => 3,
                'price' => 3300,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'facing_id' => null,
                'type_id' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 4',
                'width' => 33,
                'length' => 33,
                'unit_number' => 4,
                'price' => 3300,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'facing_id' => null,
                'type_id' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 5',
                'width' => 33,
                'length' => 33,
                'unit_number' => 5,
                'price' => 3300,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'facing_id' => null,
                'type_id' => 5,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        (new Unit())->insert($data);
    }
}
