<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $customers = User::where('is_admin', 0)
            ->when(request('search'), function ($query, $search) {
                $query->where('name', 'like', "%$search%")
                    ->orWhere('email', 'like', "%$search%");
            })
            ->withCount('orders')
            ->withSum('orders', 'total')
            ->with('order')
            ->latest()
            ->paginate(20);

        return view('backend.customer.index', compact('customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.customer.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $customer)
    {
        return view('backend.customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Customer $customer)
    {
        return redirect()->route('admin.customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $customer)
    {
        abort_if($customer->is_admin, 403, 'You can not delete admin user.');
        abort_if($customer->orders()->exists(), 403, 'You can not delete, this customer has orders.');
        abort_if($customer->reviews()->exists(), 403, 'You can not delete, this customer has reviews.');
        abort_if($customer->comments()->exists(), 403, 'You can not delete, this customer has comments.');

        $customer->delete();
        return $this->respond('Customer deleted successfully.');
    }
}
