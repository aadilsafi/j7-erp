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
        ]);

    }
}
