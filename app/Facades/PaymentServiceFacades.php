<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class PaymentServiceFacades extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'payment-service';
    }
}
