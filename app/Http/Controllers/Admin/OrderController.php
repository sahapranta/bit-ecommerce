<?php

namespace App\Http\Controllers\Admin;

use App\Enums\OrderStatusEnum;
use App\Models\Order;
use App\Jobs\SendEmail;
use Illuminate\Http\Request;
use App\Mail\OrderSuccessMail;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Notifications\OrderSuccess;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Notification;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::query()
            ->when(request('status'), function ($query, $status) {
                $query->where('status', $status);
            })
            ->when(request('search'), function ($query, $search) {
                $query->whereHas('user', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            })
            ->with('user', 'items', 'items.product')
            ->orderBy('created_at', 'desc')
            ->paginate(\AppSettings::get('default_paginate_limit', 15));


        $summary = Cache::remember('order_summary', 3600 * 6, function () {
            return Order::query()
                ->selectRaw("SUM(CASE WHEN status = 'paid' THEN 1 ELSE 0 END) as paid,
                                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed,
                                SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected")
                ->first();
        });

        $options = [
            [
                'name' => 'paid',
                'title' => 'Paid Order',
                'type' => 'info',
                'value' => $summary->paid ?? 0
            ],
            [
                'name' => 'pending',
                'title' => 'Pending Order',
                'type' => 'secondary',
                'value' => $summary->pending ?? 0
            ],
            [
                'name' => 'completed',
                'title' => 'Completed Order',
                'type' => 'success',
                'value' => $summary->completed ?? 0
            ],
            [
                'name' => 'rejected',
                'title' => 'Rejected Order',
                'type' => 'danger',
                'value' => $summary->rejected ?? 0
            ],
        ];

        return view('backend.order.index', compact('orders', 'options'));
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }


    public function show(Order $order)
    {
        $order->load('user', 'items', 'items.product', 'shippingAddress');
        return view('backend.order.show', compact('order'));
    }

    public function invoice(Request $request, Order $order)
    {
        $order->load(['items', 'shippingAddress', 'billingAddress', 'items.product']);

        if ($request->has('no-pdf')) {
            return view('frontend.store.invoice', compact('order'));
        }

        $pdf = Pdf::loadView('frontend.store.invoice', compact('order'));
        return $pdf->download("invoice-{$order->tracking_id}.pdf");
    }

    public function sendmail(Order $order)
    {
        $order->load(['items', 'shippingAddress', 'billingAddress', 'items.product', 'user']);
        // $email = $order->billingAddress?->email ?? $order->shippingAddress?->email ?? $order->user->email;
        // $details = ['email' => $email];
        // SendEmail::dispatch($details);
        // $emailJob = (new SendEmail($details, $order))->delay(now()->addMinutes(1));
        // dispatch($emailJob);
        // Mail::to($details)
        // ->cc($moreUsers)
        // ->bcc($evenMoreUsers)
        // ->queue(new OrderSuccessMail($order));
        // ->later(now()->addMinutes(1), new OrderSuccessMail($order));
        Notification::send($order->user, new OrderSuccess($order));
        return $this->respond('Email sent successfully');
    }

    public function edit(Order $order)
    {
        $order->load('user', 'items', 'items.product', 'shippingAddress');
        return view('backend.order.edit', compact('order'));
    }


    public function update(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:' . OrderStatusEnum::commaSeparated(),
            'is_gift' => 'nullable|boolean',
            'delivery_date' => 'nullable|date',
            'delivery_note' => 'nullable|string|max:255',
            'delivery_method' => 'nullable|string|max:255',
            'delivery_status' => 'nullable|string|max:255',
            'gift_message' => 'nullable|string|max:255',
        ]);

        $request->merge([
            'is_gift' => in_array($request->is_gift, ['on', '1', true, 1]) ? true : false,
        ]);

        $order->update($request->only(
            'status',
            'delivery_date',
            'delivery_note',
            'is_gift',
            'gift_message',
            'delivery_method',
            'delivery_status',
        ));

        return $this->respond('Order updated successfully');
    }

    public function destroy(Order $order)
    {
        $order->items()->delete();
        $order->delete();
        return $this->respond('Order deleted successfully');
    }
}
