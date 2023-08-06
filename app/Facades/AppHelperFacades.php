<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class AppHelperFacades extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'app-helper';
    }
}
