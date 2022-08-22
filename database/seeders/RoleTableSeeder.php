<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Role())->insert([
            [
                'name' => 'Director',
                'guard_name' => 'web',
                'default' => 1,
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GM Sales',
                'guard_name' => 'web',
                'default' => 0,
                'parent_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sales Managers',
                'guard_name' => 'web',
                'default' => 0,
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sales Persons',
                'guard_name' => 'web',
                'default' => 0,
                'parent_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Accounts',
                'guard_name' => 'web',
                'default' => 0,
                'parent_id'=> 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
