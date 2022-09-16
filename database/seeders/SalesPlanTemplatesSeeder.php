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
        //
        (new SalesPlanTemplate())->insert([
            [
                'name' => 'Signature Sales Plan',
                'slug' => 'signature-sales-plan',
                'image' => '/images/SalesTemplate/signature.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'J7-Global Sales Plan',
                'slug' => 'j7-global-sales-plan',
                'image' => '/images/SalesTemplate/j7global.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'J7-Global Payment Plan',
                'slug' => 'j7-global-payment-plan',
                'image' => '/images/SalesTemplate/j7globalpaymentPlan.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

    }
}
