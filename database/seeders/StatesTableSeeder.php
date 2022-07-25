<?php

namespace Database\Seeders;

use App\Models\State;
use Illuminate\Database\Seeder;

class StatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $states = [
            [
                'country_id' => 1,
                'name' => 'Sindh'
            ],
            [
                'country_id' => 1,
                'name' => 'Punjab'
            ],
            [
                'country_id' => 1,
                'name' => 'Khyber Pakhtunkhwa'
            ],
            [
                'country_id' => 1,
                'name' => 'Islamabad'
            ],
            [
                'country_id' => 1,
                'name' => 'Balochistan'
            ],
            [
                'country_id' => 1,
                'name' => 'Azad Kashmir'
            ],
            [
                'country_id' => 1,
                'name' => 'Gilgit Baltistan'
            ],


        ];
        (new State())->insert($states);
    }
}
