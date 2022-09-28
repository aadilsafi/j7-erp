<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Team())->insert([
            [
                'site_id' => 1,
                'name' => 'Sales Team',
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'name' => 'Recovery Team',
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'name' => 'Account Team',
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'name' => 'HR Team',
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'name' => 'Managment',
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
