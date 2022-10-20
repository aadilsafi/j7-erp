<?php

namespace Database\Seeders;

use App\Models\SalesPlan;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SalesPlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        (new SalesPlan())->insert([
            [
                'unit_id' => 1,
                'user_id' => 1,
                'stakeholder_id' => 2,
                'stakeholder_data' => '{"id":2,"site_id":1,"full_name":"Ali Raza","father_name":"Raza","occupation":"Web Developer","designation":"Laravel Developer","cnic":"1234567890124","ntn":"1234567890124","contact":"0512226044","address":"Sarai Kharbooza, Opposite E16 GT Road, Islamabad, Pakistan","comments":"Sarai Kharbooza, Opposite E16 GT Road, Islamabad, Pakistan","parent_id":0,"relation":null,"created_at":"2022-10-08T10:40:48.000000Z","updated_at":"2022-10-08T10:40:48.000000Z","deleted_at":null}',
                'lead_source_id'=> 1,
                'unit_price' => 26000,
                'total_price' => 13598000,
                'discount_percentage' => 0,
                'discount_total' => 0,
                'down_payment_percentage' => 25,
                'down_payment_total' =>3399500,
                'validity' => now(),
                'comments' => 'test',
                'approved_date' => now(),
                'status' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'unit_id' => 1,
                'user_id' => 1,
                'stakeholder_id' => 2,
                'stakeholder_data' => '{"id":2,"site_id":1,"full_name":"Ali Raza","father_name":"Raza","occupation":"Web Developer","designation":"Laravel Developer","cnic":"1234567890124","ntn":"1234567890124","contact":"0512226044","address":"Sarai Kharbooza, Opposite E16 GT Road, Islamabad, Pakistan","comments":"Sarai Kharbooza, Opposite E16 GT Road, Islamabad, Pakistan","parent_id":0,"relation":null,"created_at":"2022-10-08T10:40:48.000000Z","updated_at":"2022-10-08T10:40:48.000000Z","deleted_at":null}',
                'lead_source_id'=> 1,
                'unit_price' => 26000,
                'total_price' => 13598000,
                'discount_percentage' => 0,
                'discount_total' => 0,
                'down_payment_percentage' => 25,
                'down_payment_total' =>3399500,
                'validity' => now(),
                'comments' => 'test',
                'approved_date' => now(),
                'status' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
