<?php

namespace Database\Seeders;

use App\Models\ReceiptTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReceiptsTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $data = [
            [
                'name' => 'J7 Global',
                'slug' => 'j7_template',
                'image' => '/images/receipts/j7-gloabl-receipt.png',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ];

        foreach ($data as $item) {
            (new ReceiptTemplate())->create($item);
        }
    }
}
