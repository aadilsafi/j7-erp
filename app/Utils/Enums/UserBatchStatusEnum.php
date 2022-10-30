<?php

namespace App\Utils\Enums;

use App\Utils\Traits\EnumToArray;

enum UserBatchStatusEnum: int
{
    use EnumToArray;

    case PENDING = 1;
    case FINISHED = 2;
    case CANCELLED = 3;
}
