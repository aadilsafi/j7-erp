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
                'modelable_type' => null,
                'code' => '10',
                'name' => 'Asset',
                'level' => 1,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '1010',
                'name' => 'Non-current Asset',
                'level' => 2,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
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
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\Unit',
                'code' => '1020200001',
                'name' => 'GF-01 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '10202000011001',
                'name' => 'Zain Ali Customer A/R',
                'level' => 5,
            ],

            [
                'site_id' => 1,
                'modelable_id' => 2,
                'modelable_type' => 'App\Models\Unit',
                'code' => '1020200002',
                'name' => 'GF-02 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '10202000021001',
                'name' => 'Zain Ali Customer A/R',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => 3,
                'modelable_type' => 'App\Models\Unit',
                'code' => '1020200003',
                'name' => 'GF-03 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 4,
                'modelable_type' => 'App\Models\Unit',
                'code' => '1020200004',
                'name' => 'GF-04 Receviable',
                'level' => 4,
            ],

            [
                'site_id' => 1,
                'modelable_id' => 5,
                'modelable_type' => 'App\Models\Unit',
                'code' => '1020200005',
                'name' => 'GF-05 Receviable',
                'level' => 4,
            ],


            [
                'site_id' => 1,
                'modelable_id' => 2,
                'modelable_type' => 'App\Models\Type',
                'code' => '102021',
                'name' => 'Accounts Receviable - Hotel Suites',
                'level' => 3,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '1020210001',
                'name' => '3F-01 Receviable',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '10202100011001',
                'name' => 'Zain Ali Customer A/R',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => 3,
                'modelable_type' => 'App\Models\Type',
                'code' => '102022',
                'name' => 'Accounts Receviable - Offices',
                'level' => 3,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '102090',
                'name' => 'Cash, Bank, Clearing Account',
                'level' => 3,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '1020901000',
                'name' => 'Bank',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '10209010001001',
                'name' => 'MCB Bank',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '10209010001002',
                'name' => 'Meezan Bank - Expense',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '10209010001003',
                'name' => 'Meezan Bank - Income',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '10209010001010',
                'name' => 'Cheques Clearing Account',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '1020902000',
                'name' => 'Cash',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '10209020001001',
                'name' => 'Cash at Office',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '10209020001002',
                'name' => 'Petty Cash',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '20',
                'name' => 'Liability',
                'level' => 1,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '2010',
                'name' => 'Non-current liability',
                'level' => 2,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '2020',
                'name' => 'Current liability',
                'level' => 2,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '202010',
                'name' => 'Account Payable',
                'level' => 3,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '2020101000',
                'name' => 'Customer AP',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '20201010000001',
                'name' => 'Zain Ali Customer A/P',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '20201010000002',
                'name' => 'Ali Raza Customer A/P',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '2020102000',
                'name' => 'Dealer AP',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '20201020000001',
                'name' => 'Zain Ali Dealer A/P',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '20201020000002',
                'name' => 'Ali Raza Dealer A/P',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '2020103000',
                'name' => 'Supplier AP',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '20201030000001',
                'name' => 'Zain Ali Supplier AP',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '20201030000002',
                'name' => 'Ali Raza Supplier AP',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '30',
                'name' => 'Equity',
                'level' => 1,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '3010',
                'name' => 'Ordinary Shares',
                'level' => 2,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '301010',
                'name' => 'Ordinary Shares',
                'level' => 3,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '3010101000',
                'name' => 'Ordinary Shares',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '30101010001001',
                'name' => 'Ordinary Shares - Person',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '40',
                'name' => 'Revenue',
                'level' => 1,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '4010',
                'name' => 'Revenue',
                'level' => 2,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '401010',
                'name' => 'Revenue',
                'level' => 3,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '4010101000',
                'name' => 'Revenue',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '40101010001001',
                'name' => 'Revenue - Sales',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '40101010001002',
                'name' => 'Revenue - Cancelation Charges',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '40101010001003',
                'name' => 'Revenue - Transfer Fees',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '40101010001004',
                'name' => 'Revenue - Other Income',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '4020',
                'name' => 'Revenue Reversals',
                'level' => 2,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '402010',
                'name' => 'Revenue Reversals',
                'level' => 3,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '4020101000',
                'name' => 'Revenue Reversals',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '40201010001001',
                'name' => 'Buyback Account',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '40201010001002',
                'name' => 'Refund Account',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '40201010001003',
                'name' => 'Cancelation Account',
                'level' => 5,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '50',
                'name' => 'Cost of Goods - Expenses',
                'level' => 1,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '5010',
                'name' => 'Cost of Goods Sold',
                'level' => 2,
            ],


            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '60',
                'name' => 'Sales, General Admin - Expense',
                'level' => 1,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '6030',
                'name' => 'Sales and Marketing Expense',
                'level' => 2,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '603010',
                'name' => 'Dealer Rebate and Incentive',
                'level' => 3,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '6030101000',
                'name' => 'Dealer Rebate and Incentive',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '60301010001001',
                'name' => 'Dealer Rebate Expense',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '60301010002001',
                'name' => 'Dealer Incentive Expense',
                'level' => 5,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '603020',
                'name' => 'Profit Paid',
                'level' => 3,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '6030201000',
                'name' => 'Profit Paid',
                'level' => 4,
            ],
            [
                'site_id' => 1,
                'modelable_id' => null,
                'modelable_type' => null,
                'code' => '60302010001001',
                'name' => 'Customer Own Paid Expense',
                'level' => 5,
            ],
            // For Title Transfer
            // Customer B which is Ali Raza
            [
                'site_id' => 1,
                'modelable_id' => 1,
                'modelable_type' => 'App\Models\StakeholderType',
                'code' => '10202000011002',
                'name' => 'Ali Raza Customer A/R',
                'level' => 5,
            ],
        ];

        foreach ($data as $item) {
            (new AccountHead())->create($item);
        }
    }
}
