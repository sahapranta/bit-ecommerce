<?php

namespace App\Listeners;

use App\Events\OrderPlaced;
use App\Notifications\NotifyAdmin;
use App\Notifications\OrderSuccess;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class OrderPlacedListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(OrderPlaced $event): void
    {
        Notification::send($event->order->user, new OrderSuccess($event->order));

        event(new NotifyAdmin([
            'message' => 'New Order Placed of ' . \AppHelper::money($event->order->total),
            'action' => route('admin.orders.edit', $event->order->id)
        ]));
    }
}
