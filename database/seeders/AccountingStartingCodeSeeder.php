<?php

namespace Database\Seeders;

use App\Models\AccountingStartingCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountingStartingCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        (new AccountingStartingCode())->insert([
            [
                'site_id' => 1,
                'model' => 'App\Models\Type',
                'level_code' => '1020',
                'level' => 2,
                'starting_code' => '20',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'model' => 'App\Models\Bank',
                'level_code' => '1020',
                'level' => 2,
                'starting_code' => '90',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'model' => 'App\Models\Bank',
                'level_code' => '102090',
                'level' => 3,
                'starting_code' => '1000',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'model' => 'App\Models\Cash',
                'level_code' => '102090',
                'level' => 3,
                'starting_code' => '2000',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'model' => 'App\Models\CustomerAccountPayable',
                'level_code' => '202010',
                'level' => 4,
                'starting_code' => '1000',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'model' => 'App\Models\DealerAccountPayable',
                'level_code' => '202010',
                'level' => 4,
                'starting_code' => '2000',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'site_id' => 1,
                'model' => 'App\Models\SupplierAccountPayable',
                'level_code' => '202010',
                'level' => 4,
                'starting_code' => '2000',
                'status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

    }
}
