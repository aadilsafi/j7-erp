<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StatusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'name' => 'Open',
                'default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Token',
                'default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Partial Paid',
                'default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hold',
                'default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sold',
                'default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Request For Resale',
                'default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        (new Status())->insert($data);
    }
}
