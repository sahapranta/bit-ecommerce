<x-mail::message>
# <center>Your package has been shipped!</center>

## Hi {{ $order->mail_info['name'] }},
Thank you for choosing {{ config('app.name') }}! Item(s) from your order # **{{ $order->order_id }}** has been shipped.
<br>

We hope you will get your delivery within your expected time. You can track your order using the button below and find the order history in your account.

<x-mail::button :url="route('order.track', ['order'=>$order->order_id])">
TRACK MY ORDER
</x-mail::button>

If you have any questions or concerns, please contact us at {{ config('mail.from.address') }}. <br>

<center>Thank you for shopping with us.</center>

</x-mail::message>
