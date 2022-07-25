<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Site())->create([
            'name' => 'Aadil Heights',
            'city_id' => 1,
            'address' => 'some random address',
            'area_width' => 250,
            'area_length' => 150,
            'created_at' => now(),
            'updated_at' => now(),
        ])->siteConfiguration()->create([
            'site_max_floors' => 0,
            'floor_prefix' => 'F',
            'unit_number_digits' => 2,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
