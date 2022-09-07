<?php

namespace Database\Seeders;

use App\Models\LeadSource;
use Illuminate\Database\Seeder;

class LeadSourceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new LeadSource())->insert([
            [
                'site_id' => 1,
                'name' => 'Dealer',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'name' => 'Social Media',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'name' => 'Walking Client',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'name' => 'UAN',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'name' => 'Direct',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
