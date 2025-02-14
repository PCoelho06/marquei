<?php

namespace App\Model;

use App\Utils\EnumHelperTrait;

enum RolesEnum: string
{
    use EnumHelperTrait;

    case ROLE_USER = 'ROLE_USER';
    case ROLE_OWNER = 'ROLE_OWNER';
    case ROLE_ADMIN = 'ROLE_ADMIN';
    case ROLE_SUPER_ADMIN = 'ROLE_SUPER_ADMIN';
}
