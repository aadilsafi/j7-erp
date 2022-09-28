<?php

namespace Database\Seeders;

use App\Models\RebateIncentiveModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RebateIncentiveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        (new RebateIncentiveModel())->insert([
            [
                'site_id' => 1,
                'unit_id' => 1 ,
                'stakeholder_id' => 1,
                'stakeholder_data' => 'Stakeholder Data',
                'unit_data' => 'Unit Data',
                'deal_type' => 'Ideal Deal',
                'commision_percentage' => 5,
                'commision_total' => 10000,
                'status' => 1,
                'comments' => 'xyz',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
