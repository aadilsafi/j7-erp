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
                'name' => 'Admin',
                'guard_name' => 'web',
                'default' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sub Admin',
                'guard_name' => 'web',
                'default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Manager',
                'guard_name' => 'web',
                'default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Sub Manager',
                'guard_name' => 'web',
                'default' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
