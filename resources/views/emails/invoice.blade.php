<x-mail::message>
# <center>Thank you for your order!</center>
<br>

## Hi {{ $order->mail_info['name'] }},
Your order #**{{ $order->order_id }}** has been successfully placed. We will notify you once your order has been shipped.
You can track your order using the button below.


<x-mail::button :url="route('order.track', ['order'=>$order->order_id])">
TRACK MY ORDER
</x-mail::button>

DELIVERY DETAILS:
<x-mail::panel>
Name: {{ $order->shippingAddress->name }}, <br>
Address: {{ $order->shippingAddress->street_1 }}, {{ $order->shippingAddress->street_2 }},
{{ $order->shippingAddress->province }},
{{ $order->shippingAddress->city }},
{{ $order->shippingAddress->country }}-{{ $order->shippingAddress->postal_code }} <br>
Phone: {{ $order->shippingAddress->phone }}
</x-mail::panel>

### ORDER DETAILS:
___
<x-mail::table>
|#| Product       | Qty x Price   | Subtotal |
|-|:------------ |:-------------:| --------:|
@foreach ($order->items as $item)
| {{$loop->iteration }}.| {{ $item->product->name }} | {{ $item->quantity }} x {{ AppHelper::money($item->price) }} | {{ AppHelper::money($item->price * $item->quantity) }} |
@endforeach
| |              |   Subtotal:|{{ AppHelper::moneyWithSymbol($order->subtotal) }} |
| |             |  Discount: |{{ AppHelper::moneyWithSymbol($order->discount) }} |
| |              |       Tax: |{{ AppHelper::moneyWithSymbol($order->tax) }} |
| |              |  Shipping: |{{ AppHelper::moneyWithSymbol($order->shipping) }} |
| |             | **Total:** |**{{ AppHelper::moneyWithSymbol($order->total) }}** |
</x-mail::table>

<center>Thank you for shopping with us.</center>


</x-mail::message>
