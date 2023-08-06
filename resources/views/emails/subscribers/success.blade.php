<x-mail::message>
### Hi {{ $subscriber->name ?? 'Subscriber' }},

You have successfully subscribed to our site.
According to your subscription, you will receive emails from us.
We do not send spam emails. So you can trust us.
But you can <a href="{{ URL::signedRoute('unsubscribe', ['mail'=>$subscriber->email]) }}">unsubscribe</a> from our site at any time.

<center>Thank you for subscribing us.</center>
</x-mail::message>
