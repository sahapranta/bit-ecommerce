<?php

namespace App\Providers;

use App\Events\ConfirmedPaymentEvent;
use App\Events\NotifyAdmin;
use App\Events\OrderPlaced;
use App\Events\UnconfirmedPaymentEvent;
use App\Events\UnknownTransactionEvent;
use App\Listeners\ConfirmedPaymentListener;
use App\Listeners\NotifyAdminListener;
use App\Listeners\OrderPlacedListener;
use App\Listeners\UnconfirmedPaymentListener;
use App\Listeners\UnknownTransactionListener;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        OrderPlaced::class => [
            OrderPlacedListener::class
        ],
        NotifyAdmin::class => [
            NotifyAdminListener::class
        ],
        ConfirmedPaymentEvent::class => [
            ConfirmedPaymentListener::class
        ],
        UnconfirmedPaymentEvent::class => [
            UnconfirmedPaymentListener::class
        ],
        UnknownTransactionEvent::class => [
            UnknownTransactionListener::class
        ]
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
