<?php

namespace App\Services;

use NumberFormatter;


class MoneyService
{
    private static $numberFormatter;

    public static function inWords($amount, $locale = null): ?string
    {
        $locale = $locale !== null ? strtolower($locale) : config('app.locale', 'en');

        $amount = static::convertToNumber($amount);

        if ($amount === null) return null;

        if (!isset(self::$numberFormatter)) {
            // Initialize the NumberFormatter if not already done
            self::$numberFormatter = new NumberFormatter($locale, NumberFormatter::SPELLOUT);
        }

        return self::$numberFormatter->format($amount);
    }

    public static function convertToNumber($amount): ?float
    {
        if (is_numeric($amount)) return (float) $amount;
        $amount = preg_replace('/[^0-9\.]/', '', $amount);
        return (float) $amount;
    }

    public static function getPercent($amount, $total, $decimal=2): float
    {
        if ($total === 0) return 0;
        return round(($amount / $total) * 100, $decimal);
    }

    public static function getPercentOf($percent, $total): float
    {
        if ($total === 0) return 0;
        return round(($percent / 100) * $total, 2);
    }

    public static function getPercentChange($old, $new): float
    {
        if ($old === 0) return 0;
        return round((($new - $old) / $old) * 100, 2);
    }

    public static function getPercentChangeOf($percent, $old): float
    {
        if ($old === 0) return 0;
        return round(($percent / 100) * $old, 2);
    }

    public static function getPercentOfChange($percent, $old): float
    {
        if ($old === 0) return 0;
        return round(($percent / $old) * 100, 2);
    }
}
