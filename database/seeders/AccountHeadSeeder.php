<?php

namespace Database\Seeders;

use App\Models\AccountHead;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountHeadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'code' => '1020201001',
                'name' => 'Zain Ali Customer AR',
                'level' => 4,
            ],
            [
                'code' => '1020901001',
                'name' => 'MCB Bank',
                'level' => 4,
            ],
            [
                'code' => '1020901002',
                'name' => 'Meezan Bank - Expense',
                'level' => 4,
            ],
            [
                'code' => '1020901003',
                'name' => 'Meezan Bank - Income',
                'level' => 4,
            ],
            [
                'code' => '1020901004',
                'name' => 'Cheques Clearing Account',
                'level' => 4,
            ],
            [
                'code' => '1020902001',
                'name' => 'Cash at Office',
                'level' => 4,
            ],
            [
                'code' => '1020902002',
                'name' => 'Petty Cash',
                'level' => 4,
            ],
            [
                'code' => '2020101001',
                'name' => 'Zain Ali Customer AP',
                'level' => 4,
            ],
            [
                'code' => '2020102001',
                'name' => 'Zain Ali Dealer AP',
                'level' => 4,
            ],
            [
                'code' => '2020103001',
                'name' => 'Zain Ali Supplier AP',
                'level' => 4,
            ],
            [
                'code' => '3010101001',
                'name' => 'Ordinary Shares - Person',
                'level' => 4,
            ],
            [
                'code' => '4010102001',
                'name' => 'Revenue - Sales',
                'level' => 4,
            ],
            [
                'code' => '4010101002',
                'name' => 'Revenue - Other Income',
                'level' => 4,
            ],
            [
                'code' => '6030101001',
                'name' => 'Zain Ali Dealer Rebate Expense',
                'level' => 4,
            ],
            [
                'code' => '6030102001',
                'name' => 'Zain Ali Dealer Incentive Expense',
                'level' => 4,
            ],
            [
                'code' => '6030202001',
                'name' => 'Customer Own Paid Expense',
                'level' => 4,
            ],
        ];

        foreach ($data as $item) {
            (new AccountHead())->create($item);
        }
    }
}
