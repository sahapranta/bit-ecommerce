<?php

namespace App\Services;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class AppHelper
{
    const DECIMAL = 2;
    const BTC_DECIMAL = 9;
    const BTC_SATOSHI = 100000000;
    const BTC_REMEMBER = 60 * 60 * 4; // 4 hours

    public static function money($amount, bool $noFormat = false): string|float
    {
        if (!is_numeric($amount)) return 0;

        if ($amount < 0) {
            $amount = bcmul($amount, -1, static::DECIMAL);
            return '-' . static::money($amount);
        };

        // $amount = MoneyService::convertToNumber($amount);

        $rate  = static::getCurrencyRate() ?? 1;
        $amount = bcmul($amount, $rate, static::DECIMAL);

        if ($noFormat) return $amount;

        if (strpos($amount, '.') === false) return number_format($amount, 0);

        // format only before decimal point
        $beforeDecimal = number_format(floor($amount), 0);
        $afterDecimal = substr($amount, strpos($amount, '.'));
        // remove unwanted zeros from the end of the number
        $afterDecimal = rtrim(rtrim($afterDecimal, '0'), '.');

        return $beforeDecimal . $afterDecimal;
    }

    public static function moneyWithSymbol($amount, $symbol = null, $options = [])
    {
        if (!is_array($options)) throw new Exception('Options must be an array');

        $options = array_merge([
            'space' => true,
            'inverse' => false,
        ], $options);

        $symbol = $symbol ?? AppSettings::get('currency_symbol', '₿');

        $amount = static::money($amount);

        $spacer = $options['space'] ? ' ' : '';

        if ($options['inverse']) return $amount . $spacer . $symbol;

        return $symbol . $spacer . $amount;
    }

    public static function calculate($price, $quantity, $symbol = true, $noFormat = false): string|float
    {
        // $price = MoneyService::convertToNumber($price);
        // $quantity = MoneyService::convertToNumber($quantity);

        $amount = bcmul($price, $quantity, static::DECIMAL);
        if ($symbol) return static::moneyWithSymbol($amount);
        return static::money($amount, $noFormat);
    }

    public static function getCurrencySymbol(): string
    {
        return AppSettings::get('currency_symbol', '₿');
    }

    public static function getCurrencyCode(): string
    {
        return AppSettings::get('currency_code', 'BTC');
    }

    public static function getCurrencyName(): string
    {
        return AppSettings::get('currency_name', 'Bitcoin');
    }

    public static function getCurrencyRate(): float
    {
        return AppSettings::get('currency_rate', 1);
    }

    public static function getCurrencyRateInverse(): float
    {
        return 1 / static::getCurrencyRate();
    }

    public static function summarizeHTML($html, int $maxParagraphs = 3, int $maxCharacters = 200): string
    {
        $text = strip_tags($html);
        $paragraphs = explode('.', $text);

        $paragraphs = array_filter($paragraphs, function ($paragraph) {
            return trim($paragraph) !== '';
        });

        $summaryParagraphs = array_slice($paragraphs, 0, $maxParagraphs);

        $summary = implode(". ", $summaryParagraphs);

        // Truncate the summary to the desired character limit
        if (strlen($summary) > $maxCharacters) {
            $summary = mb_substr($summary, 0, $maxCharacters) . '...';
        }

        return $summary;
    }

    public static function placeholder($image = null): string
    {
        return $image ?? AppSettings::get('placeholder_image', asset('media/placeholder.webp'));
    }

    public static function getPercentSaved($discount, $price, $decimal): float
    {
        return MoneyService::getPercent($discount, $price, $decimal);
    }

    public static function generateTrackingId($prefix = null): string
    {
        $prefix = $prefix ?? AppSettings::get('tracking_id_prefix', 'ORD');

        return $prefix . '-' . strtoupper(uniqid());
    }

    public static function replacePlaceholders(string $template, array $data = [], $start = '{', $end = '}'): string
    {
        if (empty($template) || !is_string($template)) return '';
        if (empty($data)) return $template;
        foreach ($data as $placeholder => $value) {
            $placeholder = $start . $placeholder . $end;
            $template = str_replace($placeholder, $value, $template);
        }

        return $template;
    }

    public static function getBTCRate(bool $fresh = false)
    {
        if ($fresh) {
            Cache::forget('btc_rate');
        }

        return Cache::remember('btc_rate', static::BTC_REMEMBER, function () {
            // get the btc_rate.json file from the storage
            $file = Storage::json('btc-rate.json');
            if (empty($file)) return 1;
            return data_get($file, 'rate', 1);
        });
    }

    public static function getBTCRateInverse()
    {
        return bcdiv(1, static::getBTCRate(), static::BTC_DECIMAL) ?? 1;
    }

    public static function convertToBTC($amount, bool $symbol = true): string|float
    {
        $amount = (string) $amount;
        $rate = static::getBTCRateInverse();
        $amount = bcmul($amount, $rate, static::BTC_DECIMAL);
        return $symbol ? static::withBtcSymbol($amount) : static::withBtcSymbol($amount, null);
    }

    public static function withBtcSymbol($amount, $symbol = '₿'): string
    {
        $amount = rtrim(rtrim($amount, '0'), '.');
        return $symbol ? "$symbol $amount" : $amount;
    }
}
