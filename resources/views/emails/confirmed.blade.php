<x-mail::message>
# Payment successful!

### Hi {{ $payment->user->name }},
Your payment of {{ AppHelper::moneyWithSymbol($payment->amount) }} has been received successfully.

Thank you for your order! We will process it as soon as possible. You can track your order status by clicking the button below.


The body of your message.

<x-mail::button :url="route('order.track', ['order'=>$payment->order->order_id])">
TRACK MY ORDER
</x-mail::button>


</x-mail::message>
