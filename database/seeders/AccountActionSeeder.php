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
                'name' => 'Receipt Voucher Cash',
                'slug' => 'receipt-voucher-cash',
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
            [
                'site_id' => 1,
                'name' => 'Sales Plan DisApproval',
                'slug' => 'sales-plan-dis-approval',
            ],
            [
                'site_id' => 1,
                'name' => 'Receipt Voucher Bank',
                'slug' => 'receipt-voucher-bank',
            ],
            [
                'site_id' => 1,
                'name' => 'Cheque Clearance',
                'slug' => 'cheque-clearance',
            ],
            [
                'site_id' => 1,
                'name' => 'Cheque Bounced',
                'slug' => 'cheque-bounced-back',
            ],
            [
                'site_id' => 1,
                'name' => 'Receipt Voucher Online',
                'slug' => 'receipt-voucher-online',
            ],
            [
                'site_id' => 1,
                'name' => 'Income',
                'slug' => 'income',
            ],
            [
                'site_id' => 1,
                'name' => 'Expense',
                'slug' => 'expense',
            ],
            [
                'site_id' => 1,
                'name' => 'Non Current Asset',
                'slug' => 'non-current-asset',
            ],
            [
                'site_id' => 1,
                'name' => 'Bank',
                'slug' => 'bank',
            ],
            [
                'site_id' => 1,
                'name' => 'Equity',
                'slug' => 'equity',
            ],
            [
                'site_id' => 1,
                'name' => 'Accounts Receivable',
                'slug' => 'accounts-receivable',
            ],
            [
                'site_id' => 1,
                'name' => 'Current Asset',
                'slug' => 'current-asset',
            ],
            [
                'site_id' => 1,
                'name' => 'Accounts Payable',
                'slug' => 'accounts-payable',
            ],
            [
                'site_id' => 1,
                'name' => 'Current Liability',
                'slug' => 'current-liability',
            ],
            [
                'site_id' => 1,
                'name' => 'Long Term Liability',
                'slug' => 'long-term-liability',
            ],
            [
                'site_id' => 1,
                'name' => 'Direct Cost',
                'slug' => 'direct-cost',
            ],
            [
                'site_id' => 1,
                'name' => 'Resale',
                'slug' => 'resale',
            ],
            [
                'site_id' => 1,
                'name' => 'Rebate Incentive',
                'slug' => 'rebate-incentive',
            ],
            [
                'site_id' => 1,
                'name' => 'Dealer Incentive',
                'slug' => 'dealer-incentive',
            ],
            [
                'site_id' => 1,
                'name' => 'Reverted Receipt',
                'slug' => 'reverted-receipt',
            ],
            [
                'site_id' => 1,
                'name' => 'Title Transfer Receipt Voucher',
                'slug' => 'title-transfer-receipt-voucher',
            ],
            [
                'site_id' => 1,
                'name' => 'Receipt Voucher Other',
                'slug' => 'receipt-voucher-other',
            ],
        ];
        foreach ($data as $item) {
            (new AccountAction())->create($item);
        }
    }
}
