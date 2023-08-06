<?php

namespace App\Listeners;

use App\Models\User;
use App\Events\NotifyAdmin;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Notifications\NotifyAdmin as Notify;
use Illuminate\Support\Facades\Notification;

class NotifyAdminListener
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
    public function handle(NotifyAdmin $event): void
    {
        // Notify Admins
        $admins = User::admin()->select('name', 'id')->get();

        if (is_string($event->message)) {
            $event->message = ['message' => $event->message];
        }

        Notification::send($admins, new Notify($event->message));
    }
}
