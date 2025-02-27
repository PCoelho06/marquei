<?php

namespace App\Model;

use App\Utils\EnumHelperTrait;

enum LoginModesEnum: string
{
    use EnumHelperTrait;

    case MANAGEMENT_MODE = 'management';
    case STORE_MODE = 'store';
}
