<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Services\ProductService;
use App\Services\CategoryService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use Illuminate\Support\Facades\Cache;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        $products = Product::query()
            ->when($request->get('search', ''), function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('title', 'like', "%{$search}%")
                    ->orWhere('short_description', 'like', "%{$search}%");
            })
            ->when($request->category, function ($query, $id) {
                $query->where('category_id', $id);
            })
            ->when($request->status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($request->order, function ($query, $order) {
                if ($order === 'out') {
                    $query->where('stock', 0);
                } else if ($order === 'new') {
                    $query->where('sales', 0);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15);


        $categories = CategoryService::getAll();

        $summary = Cache::remember('products_summary', 3600 * 24, function () {
            return Product::query()
                ->selectRaw('SUM(stock) as stock, COUNT(CASE WHEN stock = 0 THEN 1 END) as out_of_stock, SUM(CASE WHEN sales = 0 THEN stock END) as new_product')
                ->first();
        });

        return view('backend.products.index', compact('products', 'summary', 'categories'));
    }

    public function create()
    {
        $tags = ProductService::getTags();
        return view('backend.products.create', compact('tags'));
    }


    public function store(ProductRequest $request)
    {
        if (is_null($request->get('short_description')) && $request->has('description')) {
            $request->merge(['short_description' => \AppHelper::summarizeHTML($request->description, 4, 115)]);
        }

        if ($request->filled('tags')) {
            $request->merge(['tags' => array_map(
                fn ($tag): string => Str::slug($tag),
                $request->tags
            )]);
        }

        $product = Product::create($request->only([
            'name', 'slug', 'title', 'short_description',
            'description', 'price', 'stock', 'status',
            'is_active', 'tags', 'category_id', 'discount',
            'upc', 'delivery_fee', 'options'
        ]) + ['user_id' => auth()->id()]);

        if ($request->has('images')) {
            foreach ($request->input('images', []) as $image) {
                $product->addMedia(storage_path('temp/uploads/') .  $image)
                    ->toMediaCollection('products');
            }
        }

        // if ($request->has('options')) {
        //     $product->options()->createMany($request->input('options'));
        // }

        return $this->backWithMessage('Product created successfully');
    }


    public function show(Product $product)
    {
        //
    }


    public function edit(Product $product)
    {
        $product->load('media');
        $mediaItems = $product->getMedia("products");

        $product->images = $mediaItems->map(function ($m) {
            return (object) [
                'id' => $m->id,
                'name' => $m->file_name,
                'size' => $m->size,
                'dataURL' => $m->getFullUrl()
            ];
        })->toArray();

        $tags = ProductService::getTags();

        return view('backend.products.edit', compact('product', 'tags'));
    }



    public function search(Request $request)
    {
        $products = Product::query()
            ->select('id', 'name', 'stock')
            ->where('name', 'like', "%{$request->search}%")
            // ->orWhere('title', 'like', "%{$request->search}%")
            // ->orWhere('short_description', 'like', "%{$request->search}%")
            ->paginate(\AppSettings::get('default_paginate_limit', 15));

        return response()->json($products);
    }

    public function stock(Request $request)
    {
        $lowStockProducts = Product::query()
            ->select('id', 'name', 'stock')
            ->where('stock', '<=', \AppSettings::get('low_stock_threshold', 10))
            ->simplePaginate(10);

        return view('backend.products.stock', compact('lowStockProducts'));
    }


    public function stockUpdate(Request $request)
    {
        $request->validate([
            'product' => 'required|numeric',
            'stock' => 'required|numeric|min:0',
            'type' => 'required|in:add,remove'
        ]);

        $product = Product::findOrFail($request->product);

        if ($request->type === 'remove') {
            if ($product->stock < $request->stock) {
                return $this->respond('Stock not available', 'error');
            }
            $product->decrement('stock', $request->stock);
        } else {
            $product->increment('stock', $request->stock);
        }

        return $this->respond('Stock updated successfully');


        // DB::beginTransaction();

        // try {
        //     foreach ($request->products as $product) {
        //         Product::where('id', $product['id'])->update(['stock' => $product['stock']]);
        //     }

        //     DB::commit();
        //     return $this->backWithMessage('Stock updated successfully');
        // } catch (\Exception $e) {
        //     DB::rollBack();
        //     return $this->backWithMessage($e->getMessage(), 'error');
        // }
    }


    public function update(ProductRequest $request, Product $product)
    {
        DB::beginTransaction();

        try {
            if ($request->has('deleted_images')) {
                $deletedImageIds = $request->input('deleted_images', []);
                $product->media()->whereIn('id', $deletedImageIds)->delete();
            }

            if ($request->has('images')) {
                foreach ($request->input('images', []) as $image) {
                    $product->addMedia(storage_path('temp/uploads/') .  $image)
                        ->toMediaCollection('products');
                }
            }

            $product->update($request->only([
                'name', 'slug', 'title', 'short_description',
                'description', 'price', 'stock', 'delivery_fee',
                'tags', 'category_id', 'discount', 'upc', 'status', 'options'
            ]));

            DB::commit();
            return $this->backWithMessage('Product updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->backWithMessage($e->getMessage(), 'error');
        }
    }


    public function destroy(Product $product)
    {
        $product->delete();
        return $this->respond('Product deleted successfully');
    }
}
