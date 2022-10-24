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
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '10',
                'name' => 'Asset',
                'level' => 1,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1010',
                'name' => 'Non-current Asset',
                'level' => 2,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020',
                'name' => 'Current Asset',
                'level' => 2,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\Type',
                'code' => '102020',
                'name' => 'Accounts Receviable - Shops',
                'level' => 3,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020200001',
                'name' => 'GF-01 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '10202000011001',
                'name' => 'Zain Ali Customer A/R',
                'level' => 5,
            ],

            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020200002',
                'name' => 'GF-02 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020200003',
                'name' => 'GF-03 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020200003',
                'name' => 'GF-05 Receviable',
                'level' => 4,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '102021',
                'name' => 'Accounts Receviable - Hotel Suites',
                'level' => 3,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020210001',
                'name' => '3F-01 Receviable',
                'level' => 4,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '102022',
                'name' => 'Accounts Receviable - Offices',
                'level' => 3,
            ],






















            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '10202000011001',
                'name' => 'Zain Ali Customer A/R',
                'level' => 5,
            ],









































            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020201002',
                'name' => 'Ali Raza Customer AR',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020901001',
                'name' => 'MCB Bank',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020901002',
                'name' => 'Meezan Bank - Expense',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020901003',
                'name' => 'Meezan Bank - Income',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020901004',
                'name' => 'Cheques Clearing Account',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020902001',
                'name' => 'Cash at Office',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '1020902002',
                'name' => 'Imprest Account',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '2020101001',
                'name' => 'Zain Ali Customer AP',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '2020101002',
                'name' => 'Ali Raza Customer AP',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '2020102001',
                'name' => 'Zain Ali Dealer AP',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '2020102002',
                'name' => 'Ali Raza Dealer AP',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '2020103001',
                'name' => 'Zain Ali Supplier AP',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '2020103002',
                'name' => 'Ali Raza Supplier AP',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '3010101001',
                'name' => 'Ordinary Shares - Person',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '4010101001',
                'name' => 'Revenue - Sales',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '4010102001',
                'name' => 'Revenue - Cancelation Charges',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '4010103001',
                'name' => 'Revenue - Transfer Fees',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '4010104001',
                'name' => 'Revenue - Other Income',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '4020101001',
                'name' => 'Buyback Account',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '4020102001',
                'name' => 'Refund Account',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '4020103001',
                'name' => 'Cancelation Account',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '6030101001',
                'name' => 'Zain Ali Dealer Rebate Expense',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '6030101002',
                'name' => 'Ali Raza Dealer Rebate Expense',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '6030102001',
                'name' => 'Zain Ali Dealer Incentive Expense',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
                'code' => '6030102002',
                'name' => 'Ali Raza Dealer Incentive Expense',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => 'null',
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
