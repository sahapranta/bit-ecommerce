<?php

namespace App\Http\Controllers\Admin;

use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pages = Page::when(request('search'), function ($query, $search) {
            $query->where('title', 'like', "%$search%");
        })
            ->latest()
            ->paginate(\AppSettings::get('default_paginate_limit', 15));

        return view('backend.page.index', compact('pages'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.page.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'slug' => 'nullable|min:3|max:255|unique:pages',
            'subtitle' => 'nullable|min:3|max:255',
            'body' => 'nullable|min:3',
            'meta_description' => 'nullable|min:3|max:255',
            'meta_keywords' => 'nullable|min:3|max:255',
            'is_active' => 'nullable|boolean',
            'options' => 'nullable|array',
        ]);

        $request->merge(['is_active' => $request->has('is_active')]);

        if (!$request->filled('meta_description') && $request->filled('body')) {
            $request->merge(['meta_description' => Str::limit(strip_tags($request->body), 120)]);
        }

        Page::create($request->only(
            'title',
            'subtitle',
            'slug',
            'body',
            'meta_description',
            'meta_keywords',
            'is_active',
            'options'
        ));

        return $this->respond('Page created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Page $page)
    {
        return view('backend.page.show', compact('page'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Page $page)
    {
        return view('backend.page.edit', compact('page'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Page $page)
    {
        $request->validate([
            'title' => 'required|min:3|max:255',
            'slug' => 'nullable|min:3|max:255|unique:pages,slug,' . $page->id,
            'subtitle' => 'nullable|min:3|max:255',
            'body' => 'nullable|min:3',
            'meta_description' => 'nullable|min:3|max:255',
            'meta_keywords' => 'nullable|min:3|max:255',
            'is_active' => 'nullable|boolean',
            'options' => 'nullable|array',
        ]);

        $request->merge(['is_active' => $request->has('is_active')]);

        $page->update($request->only(
            'title',
            'slug',
            'subtitle',
            'body',
            'meta_description',
            'meta_keywords',
            'is_active',
            'options'
        ));

        return $this->respond('Page updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Page $page)
    {
        $page->delete();

        return $this->respond('Page deleted successfully.');
    }

    public function upload(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|max:1024',
        ]);

        if ($image = $request->file('upload')) {
            // $path = $image->store('pages', 'public');
            $name = $image->getClientOriginalName();
            $name = pathinfo($name, PATHINFO_FILENAME);
            $slugged = Str::of($name)->slug('-')->lower()->__toString();
            $imageName =  $slugged . '-' . time() . '.' . $image->extension();
            // $image->move($path, $imageName);
            $image->storeAs('public/pages', $imageName);

            return response()->json([
                'success' => true,
                'url' => asset('storage/pages/' . $imageName),
            ]);
        }

        return response()->json([
            'success' => false,
            'error' => [
                'message' => 'Image not found.',
            ]
        ]);
    }
}
