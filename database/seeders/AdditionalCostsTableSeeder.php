<?php

namespace Database\Seeders;

use App\Models\AdditionalCost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdditionalCostsTableSeeder extends Seeder
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
                'site_id' => 1,
                'name' => 'Facings',
                'slug' => Str::of('Facings')->slug(),
                'parent_id' => 0,
                'has_child' => 1,
                'site_percentage' => 0,
                'applicable_on_site' => 0,
                'floor_percentage' => 0,
                'applicable_on_floor' => 0,
                'unit_percentage' => 0,
                'applicable_on_unit' => 0,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'site_id' => 1,
                'name' => 'Margla View Facings',
                'slug' => Str::of('Margla View Facings')->slug(),
                'parent_id' => 1,
                'has_child' => 0,
                'site_percentage' => 10,
                'applicable_on_site' => 1,
                'floor_percentage' => 0,
                'applicable_on_floor' => 0,
                'unit_percentage' => 15,
                'applicable_on_unit' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'site_id' => 1,
                'name' => 'Highway Facings',
                'slug' => Str::of('Highway Facings')->slug(),
                'parent_id' => 1,
                'has_child' => 0,
                'site_percentage' => 10,
                'applicable_on_site' => 1,
                'floor_percentage' => 0,
                'applicable_on_floor' => 0,
                'unit_percentage' => 15,
                'applicable_on_unit' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'site_id' => 1,
                'name' => 'Corner Charges',
                'slug' => Str::of('Corner Charges')->slug(),
                'parent_id' => 0,
                'has_child' => 0,
                'site_percentage' => 0,
                'applicable_on_site' => 0,
                'floor_percentage' => 0,
                'applicable_on_floor' => 0,
                'unit_percentage' => 10,
                'applicable_on_unit' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($data as $item) {
            (new AdditionalCost())->create($item);
        }
    }
}
