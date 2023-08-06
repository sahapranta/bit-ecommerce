<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Support;
use Illuminate\Http\Request;

class SupportController extends Controller
{
    public function index(Request $request)
    {
        $supports = Support::query()
            ->with('user')
            ->latest()
            ->paginate(\AppSettings::get('default_paginate_limit', 10));

        return view('backend.support.index', compact('supports'));
    }

    public function edit(Request $request, Support $support)
    {
        return view('backend.support.edit', compact('support'));
    }

    public function update(Request $request, Support $support)
    {
        /**
         * @todo validate and update required data
         */
        $support->update($request->all());
        $support->user->notify(new \App\Notifications\SupportUpdate($support));

        return $this->respond('Support updated successfully.');
    }

    public function destroy(Request $request, Support $support)
    {
        $support->delete();
        return $this->respond('Support deleted successfully.');
    }
}
