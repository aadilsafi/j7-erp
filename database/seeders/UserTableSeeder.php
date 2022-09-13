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
        $user = (new User())->create([
            'name' => 'Syed Aizaz Haider Shah',
            'email' => 'admin@erp.com',
            'phone_no' => '0310-0177771',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->assignRole([1]);

        $user = (new User())->create([
            'name' => 'Admin1',
            'email' => 'admin1@erp.com',
            'phone_no' => '0310-0177771',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $user->assignRole([1]);

        $user = (new User())->create([
            'name' => 'Gm Sales',
            'email' => 'gmsales@erp.com',
            'phone_no' => '0310-0177771',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user->assignRole([2]);
    }
}
