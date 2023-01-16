<?php

namespace App\Utils\Enums;

use App\Utils\Traits\EnumToArray;

enum NatureOfAccountsEnum: string
{
    use EnumToArray;

    case SALES_PLAN_APPROVAL = 'PP';
    case SALES_PLAN_DISAPPROVAL = 'SDI';
    case RECEIPT_VOUCHER = 'RV';
    case PAYMENT_VOUCHER = 'PV';
    case JOURNAL_BUY_BACK = 'JBB';
    case JOURNAL_CANCELLATION = 'JCC';
    case JOURNAL_RESALE = 'JRC';
    case JOURNAL_REFUND = 'JRF';
    case JOURNAL_TITLE_TRANSFER = 'JTT';
    case Rebate_Incentive = 'RI';
    case Dealer_Incentive = 'DI';
    case Customer_AP_Account = 'C-AP';
    case Dealer_AP_Account = 'D-AP';
    case Vendor_AP_Account = 'V-AP';
    case TITLE_TRANSFER_RECEIPT = 'TF';
    case MANUAL_ENTRY = 'ME';
    case REVERTED_MANUAL_ENTRY = 'RME';
    case INVESTOR_DEAL = 'ID';
}
