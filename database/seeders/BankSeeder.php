<?php

namespace Database\Seeders;

use App\Models\Bank;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BankSeeder extends Seeder
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
                'site_id' => 1,
                'name' => 'MCB Bank',
                'slug' => 'mcb-bank',
                'account_number' => '10209010001001',
                'branch' => 'Tarnol, Islamabad',
                'branch_code' => '1234',
                'address' => 'Tarnol, Islamabad',
                'contact_number' => '12345678991',
                'status' => true,
                'comments' => 'This is a MCB bank',
                'account_head_code'=> '10209010001001',
            ],
            [
                'site_id' => 1,
                'name' => 'Meezan Bank',
                'slug' => 'meezan-bank',
                'account_number' => '10209010001002',
                'branch' => 'Tarnol, Islamabad',
                'branch_code' => '6560',
                'address' => 'Tarnol, Islamabad',
                'contact_number' => '12345678991',
                'status' => true,
                'comments' => 'This is a Meezan bank',
                'account_head_code'=> '10209010001002',
            ],
            [
                'site_id' => 1,
                'name' => 'Meezan Bank',
                'slug' => 'meezan-bank',
                'account_number' => '10209010001003',
                'branch' => 'Mumtaz City, Islamabad',
                'branch_code' => '1650',
                'address' => 'Mumtaz City, Islamabad',
                'contact_number' => '12345678991',
                'status' => true,
                'comments' => 'This is a Meezan bank',
                'account_head_code'=> '10209010001003',
            ],

        ];

        foreach ($data as $item) {
            (new Bank())->create($item);
        }
    }
}
