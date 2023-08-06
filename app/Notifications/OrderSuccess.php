<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use App\Mail\OrderSuccess as OrderSuccessMail;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\DatabaseMessage;

class OrderSuccess extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function withDelay(object $notifiable): array
    {
        return [
            'mail' => now()->addMinutes(2),
            'database' => now()->addMinutes(1),
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): OrderSuccessMail
    {
        $details = $this->order->mail_info;

        return (new OrderSuccessMail($this->order))
            ->to($details['email'], $details['name']);
    }

    public function toDatabase(object $notifiable)
    {
        return new DatabaseMessage([
            'message' => 'Your order has been placed!',
            'action' => route('order.track', ['order' => $this->order->order_id]),
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
