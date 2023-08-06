<?php

namespace App\Http\Controllers\Admin;

use App\Models\Subscriber;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubscriberController extends Controller
{

    public function index()
    {
        $subscribers = Subscriber::query()
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%$search%");
                $query->orWhere('email', 'like', "%$search%");
            })
            ->when(request('group'), function ($query, $group) {
                $query->where('group', $group);
            })
            ->when(request('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->when(request('is_verified'), function ($query, $is_verified) {
                $query->where('is_verified', $is_verified);
            })
            ->when(request('is_unsubscribed'), function ($query, $is_unsubscribed) {
                $query->where('is_unsubscribed', $is_unsubscribed);
            })
            ->latest()
            ->paginate(\AppSettings::get('default_paginate_limit', 15));


        return view('backend.subscriber.index', compact('subscribers'));
    }


    public function create()
    {
        return view('backend.subscriber.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers',
            'name' => 'nullable|min:3|max:255',
            'group' => 'nullable',
            'phone' => 'nullable|min:3|max:255',
            'is_verified' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $request->merge(['is_active' => $request->has('is_active')]);
        $request->merge(['is_verified' => $request->has('is_verified')]);

        if ($request->is_verified == false) {
            $request->merge([
                'verification_code' => Str::uuid(),
                'expires_at' => now()->addDays(15),
            ]);
        }

        Subscriber::create($request->only(
            'email',
            'name',
            'group',
            'phone',
            'is_verified',
            'status',
            'is_active',
        ));

        return $this->respond('New Subscriber created successfully.');
    }


    public function show(Subscriber $subscriber)
    {
        // return view('backend.subscriber.show', compact('subscriber'));
    }


    public function edit(Subscriber $subscriber)
    {
        return view('backend.subscriber.edit', compact('subscriber'));
    }

    public function update(Request $request, Subscriber $subscriber)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email,' . $subscriber->id,
            'name' => 'nullable|min:3|max:255',
            'group' => 'nullable',
            'phone' => 'nullable|min:3|max:255',
            'is_verified' => 'nullable|boolean',
            'status' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        $request->merge(['is_active' => $request->has('is_active')]);
        $request->merge(['is_verified' => $request->has('is_verified')]);

        $subscriber->update($request->only(
            'email',
            'name',
            'group',
            'phone',
            'is_verified',
            'status',
            'is_active',
        ));

        return $this->respond('Subscriber updated successfully.');
    }

    public function destroy(Subscriber $subscriber)
    {
        // if subscriber has any pending email in queue, then delete them first

        $subscriber->delete();

        return $this->respond('Subscriber deleted successfully.');
    }
}
