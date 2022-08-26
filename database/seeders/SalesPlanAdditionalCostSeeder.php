<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesPlanAdditionalCost;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalesPlanAdditionalCostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        (new SalesPlanAdditionalCost())->insert([
            [
                'sales_plan_id' => 1,
                'additional_cost_id' => 2,
                'percentage' => 10,
                'amount' => 250000,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
