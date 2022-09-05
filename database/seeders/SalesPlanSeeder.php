<?php

namespace Database\Seeders;

use App\Models\SalesPlan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalesPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        (new SalesPlan())->insert([
            [
                'unit_id' => 1,
                'user_id' => 1,
                'stakeholder_id' => 1,
                'validity' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 1,
                'user_id' => 1,
                'stakeholder_id' => 1,
                'validity' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
