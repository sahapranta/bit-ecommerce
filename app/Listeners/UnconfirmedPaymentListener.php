<?php

namespace App\Listeners;

use App\Events\UnconfirmedPaymentEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UnconfirmedPaymentListener
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
    public function handle(UnconfirmedPaymentEvent $event): void
    {
        event(new NotifyAdmin([
            'message' => 'Payment received but not Confirmed  yet of ' . \AppHelper::money($event->payment->amount),
            'action' => route('admin.orders.edit', $event->payment->order_id)
        ]));
    }
}
