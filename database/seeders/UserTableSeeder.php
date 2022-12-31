<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = (new User())->create([
            'email' => 'salah@erp.com',
            'name' => 'Salah Uddin',
            'contact' => '03100177771',
            'site_id' => 1,
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole([1]);

        $user = (new User())->create([
            'email' => 'admin@erp.com',
            'name' => 'Syed Aizaz Haider Shah',
            'site_id' => 1,
            'contact' => '03100177771',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->assignRole([1]);

        $user = (new User())->create([
            'email' => 'gmsales@erp.com',
            'site_id' => 1,
            'name' => 'Gm Sales',
            'contact' => '03100177771',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->assignRole([2]);

        $user = (new User())->create([
            'email' => 'abqayyum@erp.com',
            'site_id' => 1,
            'name' => 'Sardar Abdul Quyyum',
            'contact' => '03100177771',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->assignRole([5]);
        $user = (new User())->create([
            'email' => 'admin@crm.com',
            'site_id' => 1,
            'name' => 'CRM',
            'contact' => '+923000000000',
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->assignRole([10]);
        (new Role())->find(10)->givePermissionTo(['sites.sales_plan.generateSalesPlan','sites.sales_plan.create', 'sites.sales_plan.store', 'sites.floors.units.sales-plans.index', 'sites.floors.units.sales-plans.templates.print']);
    }
}
