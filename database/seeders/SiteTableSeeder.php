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
        $data = [
            [
                'name' => 'Aadil Heights',
                'city_id' => 1,
                'address' => 'some random address',
                'area_width' => 250,
                'area_length' => 150,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ];
        (new Site())->insert($data);
    }
}
