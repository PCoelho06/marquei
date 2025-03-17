<?php

namespace App\Model;

use App\Utils\EnumHelperTrait;

enum ResourceTypeEnum: string
{
    use EnumHelperTrait;

    case MACHINE = 'machine';
    case EMPLOYEE = 'employee';
}
