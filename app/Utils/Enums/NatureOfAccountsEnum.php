<?php

namespace App\Utils\Enums;

use App\Utils\Traits\EnumToArray;

enum NatureOfAccountsEnum: string
{
    use EnumToArray;

    case SALES_PLAN_APPROVAL = 'SI';
    case SALES_PLAN_DISAPPROVAL = 'SDI';
    case RECEIPT_VOUCHER = 'RV';
    case PAYMENT_VOUCHER = 'PV';
    case JOURNAL_BUY_BACK = 'JBB';
    case JOURNAL_CANCELLATION = 'JCC';
    case JOURNAL_RESALE = 'JRC';
    case JOURNAL_REFUND = 'JRF';
    case JOURNAL_TITLE_TRANSFER = 'JTT';
}
