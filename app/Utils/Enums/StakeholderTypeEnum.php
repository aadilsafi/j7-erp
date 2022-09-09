<?php

namespace App\Utils\Enums;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

enum StakeholderTypeEnum: string
{
    case CUSTOMER = 'C';
    case VENDOR = 'V';
    case DEALER = 'D';
    case NEXT_OF_KIN = 'K';
    case LEAD = 'L';
}
