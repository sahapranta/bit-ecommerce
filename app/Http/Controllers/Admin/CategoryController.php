<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::withCount('products')
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%$search%");
            })
            ->latest()
            ->paginate(\AppSettings::get('default_paginate_limit', 15));

        return view('backend.category.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::select('id', 'name')->get();
        return view('backend.category.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'slug' => 'nullable|min:3|max:255|unique:categories',
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:300',
        ]);

        $category = Category::create($request->only('name', 'parent_id'));

        if ($request->hasFile('image')) {
            $category->addMedia($request->file('image'))->toMediaCollection('category');
        }

        return $this->respond('Category created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        $categories = Category::select('id', 'name')->get();
        return view('backend.category.edit', compact('category', 'categories'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'slug' => 'nullable|min:3|max:255|unique:categories,slug,' . $category->id,
            'parent_id' => 'nullable|exists:categories,id',
            'image' => 'nullable|image|max:300',
        ]);

        $category->update($request->only('name', 'parent_id'));

        if ($request->hasFile('image')) {
            $category->clearMediaCollection('category');
            $category->addMedia($request->file('image'))->toMediaCollection('category');
        }

        return $this->respond('Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        abort_if($category->products()->exists(), 403, 'You can\'t delete this category because it has products.');

        $category->delete();

        return $this->respond('Category deleted successfully.');
    }
}
