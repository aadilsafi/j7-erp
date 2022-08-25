<?php

namespace App\Utils\Enums;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

enum StakeholderTypeEnum: int
{
    case CUSTOMER = 1;
    case VENDOR = 2;
    case DEALER = 3;
    case NEXT_OF_KIN = 4;
    case LEAD = 5;
}
