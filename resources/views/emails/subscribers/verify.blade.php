<x-mail::message>
### Hi {{ $subscriber->name ?? 'Subscriber' }},

Please verify your email address by clicking the button below.
If you cannot see the button, then click the link below.

<x-mail::panel>
<a href="{{ $url }}">{{ $url }}</a>
</x-mail::panel>

<x-mail::button :url="url">
Verify Email
</x-mail::button>

Please ignore this email if you did not subscribe to our site.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
