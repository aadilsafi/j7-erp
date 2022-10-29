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
                'floor_unit_number' => 'GF-01',
                'net_area' => 500,
                'gross_area' => 523,
                'price_sqft' => 26000,
                'total_price' => 13598000,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'type_id' => 5,
                'status_id' => 5,
                'active' => true,
                'is_for_rebate' => true,
                'parent_id' => 0,
                'has_sub_units' => false,
                'unit_account' => [],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 2',
                'width' => 33,
                'length' => 33,
                'unit_number' => 2,
                'floor_unit_number' => 'GF-02',
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
                'is_for_rebate' => false,
                'parent_id' => 0,
                'has_sub_units' => false,
                'unit_account' => [],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 3',
                'width' => 33,
                'length' => 33,
                'unit_number' => 3,
                'floor_unit_number' => 'GF-03',
                'net_area' => 500,
                'gross_area' => 523,
                'price_sqft' => 26000,
                'total_price' => 13598000,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'type_id' => 4,
                'status_id' => 1,
                'active' => true,
                'is_for_rebate' => false,
                'parent_id' => 0,
                'has_sub_units' => false,
                'unit_account' => [],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 4',
                'width' => 33,
                'length' => 33,
                'unit_number' => 4,
                'floor_unit_number' => 'GF-04',
                'net_area' => 500,
                'gross_area' => 523,
                'price_sqft' => 26000,
                'total_price' => 13598000,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'type_id' => 4,
                'status_id' => 1,
                'active' => true,
                'is_for_rebate' => false,
                'parent_id' => 0,
                'has_sub_units' => false,
                'unit_account' => [],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'floor_id' => 1,
                'name' => 'Unit 5',
                'width' => 33,
                'length' => 33,
                'unit_number' => 5,
                'floor_unit_number' => 'GF-05',
                'net_area' => 500,
                'gross_area' => 523,
                'price_sqft' => 26000,
                'total_price' => 13598000,
                'is_corner' => false,
                'corner_id' => null,
                'is_facing' => false,
                'type_id' => 3,
                'status_id' => 1,
                'active' => true,
                'is_for_rebate' => false,
                'unit_account' => [],
                'parent_id' => 0,
                'has_sub_units' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($data as $key => $value) {
            (new Unit())->create($value);
        }
    }
}
