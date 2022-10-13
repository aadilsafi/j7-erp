<?php

namespace App\Utils\Enums;

use App\Utils\Enums\Traits\EnumToArray;

enum CustomFieldsEnum: string
{
    use EnumToArray;

    case CHECKBOX = 'checkbox';
    case DATE = 'date';
    case EMAIL = 'email';
    case NUMBER = 'number';
    case PASSWORD = 'password';
    case RADIO = 'radio';
    case SELECT = 'select';
    case TEXT = 'text';
    case TEXTAREA = 'textarea';
}
