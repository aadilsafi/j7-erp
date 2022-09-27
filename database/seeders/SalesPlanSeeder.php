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
                'lead_source_id'=> 1,
                'unit_price' => 26000,
                'total_price' => 13598000,
                'discount_percentage' => 0,
                'discount_total' => 0,
                'down_payment_percentage' => 25,
                'down_payment_total' =>3399500,
                'validity' => now(),
                'comments' => 'test',
                'approved_date' => now(),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 1,
                'user_id' => 1,
                'stakeholder_id' => 2,
                'lead_source_id'=> 1,
                'unit_price' => 26000,
                'total_price' => 13598000,
                'discount_percentage' => 0,
                'discount_total' => 0,
                'down_payment_percentage' => 25,
                'down_payment_total' =>3399500,
                'validity' => now(),
                'comments' => 'test',
                'approved_date' => now(),
                'status' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
