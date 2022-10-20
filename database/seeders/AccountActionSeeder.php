<?php

namespace Database\Seeders;

use App\Models\AccountAction;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AccountActionSeeder extends Seeder
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
                'name' => 'Sales Plan Approval',
                'slug' => 'sales-plan-approval',
            ],
            [
                'site_id' => 1,
                'name' => 'Receipt Voucher',
                'slug' => 'receipt-voucher',
            ],
            [
                'site_id' => 1,
                'name' => 'Buyback',
                'slug' => 'buyback',
            ],
            [
                'site_id' => 1,
                'name' => 'Payment Voucher',
                'slug' => 'payment-voucher',
            ],
            [
                'site_id' => 1,
                'name' => 'Refund',
                'slug' => 'refund',
            ],
            [
                'site_id' => 1,
                'name' => 'Cancellation',
                'slug' => 'cancellation',
            ],
            [
                'site_id' => 1,
                'name' => 'Title Transfer',
                'slug' => 'title-transfer',
            ],
        ];

        foreach ($data as $item) {
            (new AccountAction())->create($item);
        }
    }
}
