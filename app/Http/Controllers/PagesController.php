<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function show(Request $request, Page $page)
    {
        abort_if(!$page->is_active, 404);
        return view('pages.show', compact('page'));
    }
}
