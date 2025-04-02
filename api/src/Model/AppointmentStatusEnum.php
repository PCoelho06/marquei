<?php

namespace App\Model;

use App\Utils\EnumHelperTrait;

enum AppointmentStatusEnum: string
{
    use EnumHelperTrait;

    case SCHEDULED = 'scheduled';
    case CANCELED = 'canceled';
    case COMPLETED = 'completed';
    case NO_SHOW = 'no_show';
}
