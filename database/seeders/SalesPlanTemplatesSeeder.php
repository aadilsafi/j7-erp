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
                'name' => 'Template 01',
                'slug' => 'template-01',
                'image' => '/images/SalesTemplate/template01.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Template 02',
                'slug' => 'template-02',
                'image' => '/images/SalesTemplate/template02.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);

    }
}
