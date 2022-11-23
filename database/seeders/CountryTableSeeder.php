<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Country())->create([
            'name' => 'Pakistan',
            'short_label' => 'PK'
        ]);
        (new Country())->create([
            'name' => 'Afghanistan',
            'short_label' => 'AFG'
        ]);
    }
}
