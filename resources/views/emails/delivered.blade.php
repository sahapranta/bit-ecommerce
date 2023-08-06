<x-mail::message>
# <center>Your package has been delivered!</center>

## Hi {{ $order->mail_info['name'] }},
Thank you for choosing {{ config('app.name') }}! Item(s) from your order # {{ $order->order_id }} has been delivered.
<br>

We hope you are enjoying your order and would love to hear what you think of your purchase. Your review will help many shoppers make the right choice.

<x-mail::button :url="''">
SHARE MY REVIEW
</x-mail::button>

<center>Thank you for shopping with us.</center>
</x-mail::message>
