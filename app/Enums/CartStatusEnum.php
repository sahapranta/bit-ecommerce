<?php


namespace App\Enums;

use App\Traits\EnumHelper;

enum CartStatusEnum: string
{
    use EnumHelper;

    case OPEN = 'open';
    case PURCHASED = 'purchased';
    case CANCELED = 'canceled';
    case SUSPENDED = 'suspended';
}
