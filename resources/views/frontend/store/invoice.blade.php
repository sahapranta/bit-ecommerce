@extends('layouts.invoice')

@section('content')
@php
$bitcoin_img = '<img src="'.public_path('media/bitcoin-sm.png') . '" alt="&#8383;" height="12px">';
@endphp
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            @if (AppSettings::get('invoice_logo', false))
                            <img src="{{ public_path(AppSettings::get('invoice_logo', 'media/invoice-logo.png')) }}" style="width: 100%; max-width: 300px" alt="LOGO" onerror="this.onerror=null;this.src='<?= asset(AppSettings::get('invoice_logo', 'media/invoice-logo.png')) ?>'"/>
                            @else
                            <h5 class="h3 my-0">{{ AppSettings::get('site_name') }}</h5>
                            <small class="text-muted" style="line-height: normal;">{!! AppSettings::get('address')  !!}</small>
                            @endif
                        </td>

                        <td>
                            Invoice #: {{$order->tracking_id}}<br />
                            Created: {{ $order->created_at->format('F d, Y') }}<br />
                            Status: {{ $order->status->value }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table class="address">
                    <tr>
                        <th class="text-left">Shipping Address</th>
                        <th class="text-right">Billing Address</th>
                    </tr>
                    <tr>
                        <td>
                            {!! $order->shippingAddress->full_address_html !!}
                        </td>

                        <td>
                            {!! $order->billingAddress?$order->billingAddress->full_address_html :$order->shippingAddress->full_address_html !!}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- <tr class="heading">
            <td>Payment Method</td>

            <td>Check #</td>
        </tr>

        <tr class="details">
            <td>Check</td>

            <td>1000</td>
        </tr> -->

        <tr class="heading">
            <td># Item</td>
            <td>Price</td>
        </tr>

        @foreach ($order->items as $item)
        <tr class="item @if ($loop->last) last @endif">
            <td>{{ $loop->index + 1 }}. {{ $item->product->name }} x {{ $item->quantity }}</td>

            <td>{{ AppHelper::calculate($item->product->price, $item->quantity, symbol:false) }}</td>
        </tr>
        @endforeach

        <tr class="total">
            <td></td>

            <td><span class="float-left">{{ __('Subtotal') }}: </span> {{ AppHelper::moneyWithSymbol($order->subtotal) }}</td>
        </tr>
        @settings('tax_enabled')
        <tr class="total">
            <td></td>

            <td><span class="float-left">{{ __('Tax') }} ({{ AppSettings::get('tax_rate', 0) }}%): </span> {{ moneyWithSymbol::money($order->tax) }}</td>
        </tr>
        @endsettings
        <tr class="total">
            <td></td>

            <td><span class="float-left">{{ __('Discount') }}: </span> {{ AppHelper::moneyWithSymbol($order->discount) }}</td>
        </tr>
        <tr class="total">
            <td></td>

            <td><span class="float-left">{{ __('Shipping Fee') }}: </span> {{ AppHelper::moneyWithSymbol($order->shipping) }}</td>
        </tr>
        <tr class="total">
            <td></td>
            <td><span class="float-left">{{ __('Total') }}: </span> {{ AppHelper::moneyWithSymbol($order->total) }}</td>
        </tr>
        <tr class="total">
            <td></td>
            <td><span class="float-left">{{ __('Total in BTC') }}: </span> {!! $bitcoin_img !!} {{ AppHelper::moneyWithSymbol($order->btc_total) }}</td>
        </tr>
    </table>
    <p><b>Note</b>: {{ $order->delivery_note }}</p>
    <h4 class="text-center mt-5">{{ __('Thank you for shopping with us.') }}</h4>
</div>
@endsection