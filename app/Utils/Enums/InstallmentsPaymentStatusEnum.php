<?php

namespace App\Utils\Enums;

use App\Utils\Traits\EnumToArray;

enum InstallmentsPaymentStatusEnum: string
{
    use EnumToArray;

    case UNPAID = 'unpaid';
    case PAID = 'paid';
    case PARTIALLY_PAID = 'partially_paid';
}
