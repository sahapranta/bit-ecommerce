<?php


namespace App\Enums;

enum StatusEnum: string
{
    use \App\Traits\EnumHelper;

    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING = 'pending';
    case SUSPENDED = 'suspended';
}
