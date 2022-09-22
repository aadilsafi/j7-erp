<?php

namespace Database\Seeders;

use App\Models\UnitStakeholder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UnitStakeholdersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new UnitStakeholder())->insert([
            [
                'site_id' => 1,
                'unit_id' => 1,
                'stakeholder_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
