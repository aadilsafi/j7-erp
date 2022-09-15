<?php

namespace App\Utils\Enums;

use App\Utils\Enums\Traits\EnumToArray;

enum InstallmentsPaymentStatusEnum: string
{
    use EnumToArray;

    case UNPAID = 'unpaid';
    case PAID = 'paid';
    case PARTIALLY_PAID = 'partially_paid';
}
