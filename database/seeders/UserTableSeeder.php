<?php

namespace Database\Seeders;

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
        $user = (new User())->updateOrCreate([
            'site_id' => 1,
            'name' => 'Syed Aizaz Haider Shah',
            'email' => 'admin@erp.com',
            'contact' => '03100177771',
            'password' => Hash::make('password'),
        ], [
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
            'remember_token' => Str::random(10),
        ]);
        $user->assignRole([1]);

        $user = (new User())->updateOrCreate([
            'site_id' => 1,
            'name' => 'Admin1',
            'email' => 'admin1@erp.com',
            'contact' => '03100177771',
            'password' => Hash::make('password'),
        ], [
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->assignRole([1]);

        $user = (new User())->updateOrCreate([
            'site_id' => 1,sites.sales_plan.show
            'name' => 'Gm Sales',
            'email' => 'gmsales@erp.com',
            'contact' => '03100177771',
            'password' => Hash::make('password'),
        ], [
            'remember_token' => Str::random(10),
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = (new User())->updateOrCreate([
            'site_id' => 1,
            'name' => 'CRM',
            'email' => 'admin@crm.com',
            'contact' => '+923000000000',sites.sales_plan.show
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->assignRole([10]);
        $user->givePermissionTo(['sites.sales_plan.create', 'sites.sales_plan.store', 'sites.sales_plan.show']);
    }
}
