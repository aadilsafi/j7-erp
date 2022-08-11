<?php

namespace App\Utils\Enums;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

enum UserBatchActionsEnum: int
{
    case COPY_UNITS = 1;
    case COPY_FLOORS = 2;
    case EMAIL_NOTIFICATIONS = 3;
    case SMS_NOTIFICATIONS = 4;
    case PUSH_NOTIFICATIONS = 5;
}