<?php

namespace App\Utils\Enums;

use App\Utils\Enums\Traits\EnumToArray;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

enum StakeholderTypeEnum: string
{
    use EnumToArray;

    case CUSTOMER = 'C';
    case VENDOR = 'V';
    case DEALER = 'D';
    case NEXT_OF_KIN = 'K';
    case LEAD = 'L';
}
