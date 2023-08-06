@extends('layouts.backend')

@section('css')
<style>
    .table-bordered> :not(caption)>*>* {
        border-width: 1px 0px 0px 0px !important;
    }

    @page {
        size: A4;
        margin: 0;
    }

    @media print {
        .content {
            padding: 0 !important;
            print-color-adjust: exact;
        }
    }
</style>
@endsection

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                Invoice: #{{$order->tracking_id}}
            </h3>
            <div class="block-options">
                <button type="button" class="btn btn-sm btn-alt-primary me-1" onclick="window.print()">
                    <i class="fa fa-print"></i> Print
                </button>
                <a download class="btn btn-sm btn-alt-warning me-1" href="{{ route('admin.orders.invoice', $order->id) }}">
                    <i class="fa fa-file-pdf"></i> PDF
                </a>
                <a type="button" class="btn btn-sm btn-alt-info" href="{{ route('admin.orders.mail', $order->id) }}">
                    <i class="fa fa-envelope"></i> MAIL
                </a>
            </div>
        </div>
        <div class="block-content">
            <div class="invoice-box">
                <table class="table table-bordered">
                    <tr class="top">
                        <td colspan="4">
                            <div class="d-flex justify-content-between">
                                <div class="title">
                                    @if(AppSettings::get('invoice_logo'))
                                    <img src="{{ asset(AppSettings::get('invoice_logo', 'media/invoice-logo.png')) }}" style="width: 100%; max-width: 300px" />
                                    @else
                                    <h2 class="h3 mb-0">{{ config('app.name') }}</h2>
                                    <small class="text-muted">{{ __('79 Swamibag, Dhaka, Bangladesh')  }}</small>
                                    @endif
                                </div>

                                <div>
                                    Status: {{ $order->status->value }}<br />
                                    Date: {{ $order->created_at->format('F d, Y') }}<br />
                                    <!-- Due: February 1, 2015 -->
                                </div>
                            </div>
                        </td>
                    </tr>

                    <tr class="information">
                        <td colspan="4" class="p-0">
                            <table class="table table-striped table-bordered table-sm mb-0">
                                <tr>
                                    <th>Shipping Address</th>
                                    <th class="text-end">Billing Address</th>
                                </tr>
                                <tr>
                                    <td class="ps-2">
                                        {!! $order->shippingAddress->full_address_html !!}
                                    </td>
                                    <td class="text-end pe-2">
                                        {!! $order->billingAddress?$order->billingAddress->full_address_html :$order->shippingAddress->full_address_html !!}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr class="heading">
                        <th class="bg-light">#</th>
                        <th class="bg-light">Item</th>
                        <th class="text-center bg-light">Qty x Price</th>
                        <th class="text-end bg-light">Total</th>
                    </tr>

                    @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $loop->index + 1 }}.</td>
                        <td width="60%">{{ $item->product->name }}</td>
                        <td class="text-center">{{ $item->quantity }} x {{ AppHelper::money($item->product->price) }}</td>
                        <td class="text-end">{{ AppHelper::calculate($item->product->price, $item->quantity) }}</td>
                    </tr>
                    @endforeach

                    <tr class="fw-bold border-0">
                        <td class="border-0" colspan="2"></td>
                        <td class="border-top">{{ __('Subtotal') }}</td>
                        <td class="text-end border-top">{{ AppHelper::moneyWithSymbol($order->subtotal) }}</td>
                    </tr>
                    @settings('tax_enabled')
                    <tr class="fw-bold border-0">
                        <td class="border-0" colspan="2"></td>
                        <td class="border-top">{{ __('Tax') }} ({{ AppSettings::get('tax_rate', 0) }}%)</td>
                        <td class="text-end border-top">{{ AppHelper::moneyWithSymbol($order->tax) }}</td>
                    </tr>
                    @endsettings
                    <tr class="fw-bold border-0">
                        <td class="border-0" colspan="2"></td>
                        <td class="border-top">{{ __('Discount') }}</td>
                        <td class="text-end border-top">{{ AppHelper::moneyWithSymbol($order->discount) }}</td>
                    </tr>
                    <tr class="fw-bold border-0">
                        <td class="border-0" colspan="2"></td>
                        <td class="border-top">{{ __('Shipping Fee') }}</td>
                        <td class="text-end border-top">{{ AppHelper::moneyWithSymbol($order->shipping) }}</td>
                    </tr>
                    <tr class="fw-bold border-0">
                        <td class="border-0" colspan="2"></td>
                        <td class="bg-light border-top">{{ __('Total') }}</td>
                        <td class="text-end border-top bg-light">{{ AppHelper::moneyWithSymbol($order->total) }}</td>
                    </tr>
                </table>
                <p><b>Note</b>: {{ $order->delivery_note }}</p>
                <h4 class="text-center py-4 h4">{{ __('Thank you for shopping with us.') }}</h4>
            </div>
        </div>
    </div>
</div>
@endsection