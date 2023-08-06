<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;

class CategoryService
{
    public static function getAll()
    {
        return Cache::rememberForever(
            'categories',
            fn () => Category::query()->select('id', 'name', 'slug')->get()
        );
    }


    public static function withProductCounts()
    {
        return Cache::rememberForever(
            'categories_with_product_counts',
            fn () => Category::query()
                ->select('id', 'name', 'slug')
                ->withCount('products')
                ->get()
        );
    }

    public static function withSalesCounts()
    {
        return Cache::rememberForever(
            'categories_with_sales_counts',
            fn () => Category::query()
                ->select('id', 'name', 'slug')
                ->withSum('products', 'sales')
                ->get()
        );
    }

    public static function withStockCounts()
    {
        return Cache::rememberForever(
            'categories_with_stock_counts',
            fn () => Category::query()
                ->select('id', 'name', 'slug')
                ->withSum('products', 'stock')
                ->get()
        );
    }


    public static function homeCategories()
    {
        return Cache::rememberForever(
            'home_categories',
            fn () => Category::select('id', 'name', 'slug')->latest()->limit(10)->get()
        );
    }

    public static function getTreeList()
    {
        Cache::forget('categories_tree_list');
        return Cache::rememberForever(
            'categories_tree_list',
            function () {
                $categories =  Category::query()
                    ->select('id', 'name', 'parent_id')
                    ->with('children:id,name,parent_id')
                    ->whereNull('parent_id')
                    ->get();

                return static::mapRecursive($categories);
            }
        );
    }

    public static function mapRecursive($array)
    {
        $cleanedCategories = [];

        foreach ($array as $category) {
            $cleaned = $category->only(['id', 'name']);

            if (!empty($category['children'])) {
                $cleaned['children'] = static::mapRecursive($category->children);
            }

            $cleanedCategories[] = $cleaned;
        }

        return $cleanedCategories;
    }

    public static function cleanCache()
    {
        Cache::forget('categories');
        Cache::forget('categories_with_product_counts');
        Cache::forget('categories_with_sales_counts');
        Cache::forget('categories_with_stock_counts');
        Cache::forget('home_categories');
        Cache::forget('categories_tree_list');
    }
}
