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
                'floor_unit_number' => '101',
                'net_area' => 500,
                'gross_area' => 523,
                'price_sqft' => 26000,
                'total_price' => 13598000,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'type_id' => 5,
                'status_id' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 2',
                'width' => 33,
                'length' => 33,
                'unit_number' => 2,
                'floor_unit_number' => '102',
                'net_area' => 500,
                'gross_area' => 523,
                'price_sqft' => 26000,
                'total_price' => 13598000,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'type_id' => 5,
                'status_id' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 3',
                'width' => 33,
                'length' => 33,
                'unit_number' => 3,
                'floor_unit_number' => '103',
                'net_area' => 500,
                'gross_area' => 523,
                'price_sqft' => 26000,
                'total_price' => 13598000,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'type_id' => 5,
                'status_id' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 4',
                'width' => 33,
                'length' => 33,
                'unit_number' => 4,
                'floor_unit_number' => '104',
                'net_area' => 500,
                'gross_area' => 523,
                'price_sqft' => 26000,
                'total_price' => 13598000,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'type_id' => 5,
                'status_id' => 2,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 5',
                'width' => 33,
                'length' => 33,
                'unit_number' => 5,
                'floor_unit_number' => '105',
                'net_area' => 500,
                'gross_area' => 523,
                'price_sqft' => 26000,
                'total_price' => 13598000,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'type_id' => 5,
                'status_id' => 1,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        (new Unit())->insert($data);
    }
}
