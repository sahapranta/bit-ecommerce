<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AppSettingsFacades extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'app-settings';
    }
}
