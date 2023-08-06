<?php

namespace App\Listeners;

use App\Events\NotifyAdmin;
use App\Events\ConfirmedPaymentEvent;
use App\Notifications\PaymentConfirmed;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;

class ConfirmedPaymentListener
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
    public function handle(ConfirmedPaymentEvent $event): void
    {
        Notification::send($event->payment->user, new PaymentConfirmed($event->payment));

        event(new NotifyAdmin([
            'message' => 'New Payment Confirmed of ' . \AppHelper::money($event->payment->amount),
            'action' => route('admin.orders.edit', $event->payment->order_id)
        ]));
    }
}
