<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    public static $keys = [
        'price_range',
        'product_tags',
    ];

    public static function getPriceRange()
    {
        return Cache::rememberForever('price_range', function () {
            $price = Product::query()
                ->selectRaw('MIN(price) as min, MAX(price) as max')
                ->first();
            return collect($price)->map(fn ($item): int => round($item));
        });
    }

    public static function getTags()
    {
        return Cache::rememberForever('product_tags', function () {
            return  Product::query()
                ->whereNotNull('tags')
                ->select('tags')
                ->pluck('tags')
                ->flatMap(fn ($tag) => $tag)
                ->unique()
                ->toArray();
        });
    }


    public static function flush()
    {
        foreach (self::$keys as $key) {
            Cache::forget($key);
        }
    }
}
