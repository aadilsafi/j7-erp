<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountLedgerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            //Sales Plan Approve
            [
                'site_id' => 1,
                'account_head_code' => '10202000011001',
                'account_action_id' => 1,
                'credit' => 0,
                'debit' => 10000000,
                'balance' => 10000000,
                'nature_of_account' => 'SI',
                'sales_plan_id' => 1,
                'status' => true,
            ],
            [
                'site_id' => 1,
                'account_head_code' => '40101010001001',
                'account_action_id' => 1,
                'credit' => 10000000,
                'debit' => 0,
                'balance' => 0,
                'nature_of_account' => 'SI',
                'sales_plan_id' => 1,
                'status' => true,
            ],

            // Receipt Voucher
            [
                'site_id' => 1,
                'account_head_code' => '10209020001001',
                'account_action_id' => 2,
                'credit' => 0,
                'debit' => 2500000,
                'balance' => 7500000,
                'nature_of_account' => 'RV',
                'sales_plan_id' => 1,
                'status' => true,
            ],
            [
                'site_id' => 1,
                'account_head_code' => '10202000011001',
                'account_action_id' => 2,
                'credit' => 2500000,
                'debit' => 0,
                'balance' => 7500000,
                'nature_of_account' => 'RV',
                'sales_plan_id' => 1,
                'status' => true,
            ],

            // Buy Back Account
            [
                'site_id' => 1,
                'account_head_code' => '40201010001001',
                'account_action_id' => 3,
                'credit' => 0,
                'debit' => 10000000,
                'balance' => 10000000,
                'nature_of_account' => 'JBB',
                'sales_plan_id' => 1,
                'status' => true,
            ],
            [
                'site_id' => 1,
                'account_head_code' => '10202000011001',
                'account_action_id' => 3,
                'credit' => 7500000,
                'debit' => 0,
                'balance' => 2500000,
                'nature_of_account' => 'JBB',
                'sales_plan_id' => 1,
                'status' => true,
            ],
            [
                'site_id' => 1,
                'account_head_code' => '20201010000001',
                'account_action_id' => 3,
                'credit' => 2500000,
                'debit' => 0,
                'balance' => 0,
                'nature_of_account' => 'JBB',
                'sales_plan_id' => 1,
                'status' => true,
            ],
            [
                'site_id' => 1,
                'account_head_code' => '60302010001001',
                'account_action_id' => 3,
                'credit' => 0,
                'debit' => 500000,
                'balance' => 500000,
                'nature_of_account' => 'JBB',
                'sales_plan_id' => 1,
                'status' => true,
            ],
            [
                'site_id' => 1,
                'account_head_code' => '20201010000001',
                'account_action_id' => 3,
                'credit' => 500000,
                'debit' => 0,
                'balance' => 0,
                'nature_of_account' => 'JBB',
                'sales_plan_id' => 1,
                'status' => true,
            ],

            // Payment Voucher
            [
                'site_id' => 1,
                'account_head_code' => '20201010000001',
                'account_action_id' => 4,
                'credit' => 0,
                'debit' => 3000000,
                'balance' => 3000000,
                'nature_of_account' => 'JBB',
                'sales_plan_id' => 1,
                'status' => true,
            ],
            [
                'site_id' => 1,
                'account_head_code' => '10209020001001',
                'account_action_id' => 4,
                'credit' => 3000000,
                'debit' => 0,
                'balance' => 0,
                'nature_of_account' => 'JBB',
                'sales_plan_id' => 1,
                'status' => true,
            ],
        ];
    }
}
