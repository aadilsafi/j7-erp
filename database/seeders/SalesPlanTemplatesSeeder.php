<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SalesPlanTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalesPlanTemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SalesPlanTemplate::truncate();
        (new SalesPlanTemplate())->insert([
            [
                'name' => 'Sales Plan Template 01',
                'slug' => 'signature-sales-plan',
                'image' => '/images/SalesTemplate/signature.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sales Plan Template 02',
                'slug' => 'j7-global-payment-plan',
                'image' => '/images/SalesTemplate/j7globalpaymentPlan.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

    }
}
