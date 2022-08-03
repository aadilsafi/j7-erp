<?php

namespace App\Utils\Enums;

enum UserBatchStatusEnum: int
{
    case PENDING = 1;
    case FINISHED = 2;
    case CANCELLED = 3;
}
