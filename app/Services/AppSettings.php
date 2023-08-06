<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class AppSettings
{
    const CACHE_KEY = 'settings';

    public static function get($key, $default = null)
    {
        // if (Schema::hasTable('settings')) {

        $settings = Cache::rememberForever(
            self::CACHE_KEY,
            fn () => Setting::all()
        );

        $setting = $settings->where('key', $key)->first();

        if ($setting) {
            $value = match ($setting->type) {
                'checkbox' =>   (bool) $setting->value,
                'key-value' =>   json_decode($setting->value, true, 512, JSON_UNESCAPED_UNICODE),
                default =>  $setting->value,
            };

            return $value;
        }

        return $default;
    }

    public static function set($key, $value)
    {

        $setting = Setting::where('key', $key)->first();

        if ($setting) {
            $setting->update(['value' => $value]);
        } else {
            Setting::create([
                'key' => $key,
                'value' => $value,
            ]);
        }

        Cache::forget(self::CACHE_KEY);
    }

    public static function forget($key)
    {
        if (Schema::hasTable('settings')) {
            $setting = Setting::where('key', $key)->first();

            if ($setting) {
                $setting->delete();
            }

            Cache::forget(self::CACHE_KEY);
        }
    }

    public static function all()
    {
        return Cache::rememberForever(
            self::CACHE_KEY,
            fn () => Setting::all()
        );
    }

    public static function asFlatArray(): array
    {
        return static::all()
            ->flatMap(fn ($setting): array => [$setting->key => $setting->value])
            ->toArray();
    }

    public static function clear()
    {
        // Setting::truncate();
        Cache::forget(self::CACHE_KEY);
    }

    public static function has($key)
    {

        $settings = Cache::rememberForever(
            self::CACHE_KEY,
            fn () => Setting::all()
        );

        return $settings->where('key', $key)->first();
    }

    public static function getOrSet($key, $value)
    {

        $setting = Setting::where('key', $key)->first();

        if ($setting) {
            return $setting->value;
        } else {
            Setting::create([
                'key' => $key,
                'value' => $value,
            ]);

            Cache::forget(self::CACHE_KEY);

            return $value;
        }
    }

    public static function getOrSetArray($key, $value)
    {

        $setting = Setting::where('key', $key)->first();

        if ($setting) {
            return json_decode($setting->value);
        } else {
            Setting::create([
                'key' => $key,
                'value' => json_encode($value),
            ]);

            Cache::forget(self::CACHE_KEY);

            return $value;
        }
    }


    /**
     * @return void
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
