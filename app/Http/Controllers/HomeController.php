<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $products = Product::published()
            ->with('media')
            ->orderBy('id', 'desc')
            // ->get();
            ->cursorPaginate(\AppSettings::get('product_per_page', 12));

        return view('landing', compact('products'));
    }

    public function category(Category $category)
    {
        $products = $category->products()
            ->with('media')
            ->published()
            ->orderBy('id', 'desc')
            // ->get();
            ->cursorPaginate(\AppSettings::get('product_per_page', 12));

        return view('frontend.store.category', compact('category', 'products'));
    }


    public function productByTags(Request $request)
    {
        $tag = $request->tag;
        $products = Product::published()
            ->with('media')
            ->whereJsonContains('tags', $tag)
            ->orderBy('id', 'desc')
            // ->get();
            ->cursorPaginate(\AppSettings::get('product_per_page', 12));

        return view('frontend.store.tags', compact('products', 'tag'));
    }
}
