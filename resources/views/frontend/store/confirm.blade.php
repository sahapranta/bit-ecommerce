@extends('layouts.simple')

@section('content')
<x-hero class="pb-5">
    <h1 class="h2 text-white mb-2">
        <i class="fas fa-check text-white-75 me-1"></i> Order Confirmed
    </h1>
    <h2 class="h4 fw-normal text-white-75 mb-0">Thank you for shopping from our store. Your order has been confirmed.</h2>
    @php
    $paymentLink = PaymentService::getPaymentLink($order->payment);
    @endphp
    <!-- <div class="text-center"> -->
    <a class="btn btn-warning px-5 py-2 text-uppercase mt-4 fw-bold d-flex align-items-center fs-lg justify-content-center" style="width:max-content;margin: auto;" href="{{ $paymentLink }}">
        <img src="{{ asset('media/bitcoin-logo.webp') }}" alt="bitcoin logo" width="25" class="me-2">
        Pay Now
    </a>
    <!-- </div> -->

</x-hero>

<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="row">
                <div class="col-md-9">
                    <div class="block block-rounded bg-light shadow">
                        <!-- <div class="block-header block-header-default">
                            <h3 class="block-title">
                                Order Summary
                            </h3>
                        </div> -->
                        <div class="p-2">
                            <div class="table-responsive">
                                <table class="table table-striped table-sm mb-0">

                                    <tr>
                                        <td class="ps-4">{{ __('Tracking ID:') }}</td>
                                        <td class="ps-2 d-flex justify-content-between align-items-center">
                                            <strong>#{{ $order->tracking_id }}</strong>
                                            <button class="btn btn-sm btn-alt-secondary py-0 copy-me" data-info="{{ $order->tracking_id }}" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Copy') }}">
                                                <i class="fas fa-copy"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">{{ __('Order Date:') }}</td>
                                        <td class="ps-2"><strong>{{ $order->created_at->format('F d, Y') }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">{{ __('Order Status:') }}</td>
                                        <td class="ps-2 fw-bold text-uppercase">{{ $order->status->value }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">{{ __('Order Total:') }}</td>
                                        <td class="ps-2 fw-bold">{{ AppHelper::moneyWithSymbol($order->total) }}</td>
                                    </tr>
                                    <tr>
                                        <td class="ps-4">{{ __('Delivery Method:') }}</td>
                                        <td class="ps-2 fw-bold">{{ $order->delivery_method }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="block block-rounded bg-light shadow">
                        <div class="p-2 text-center">
                            <div class="py-2">{!! PaymentService::getPaymentLinkQr($order->payment) !!}</div>
                            <span class="text-uppercase fw-bold pb-1">{{ __('Scan to Pay') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-8 offset-md-2 mx-auto">
            <div class="block block-rounded bg-light shadow">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        {{ __('Order Summary') }}
                    </h3>
                    <div class="block-options">
                        <button class="btn btn-sm btn-alt-warning" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Download Invoice') }}">
                            <i class="fas fa-print"></i>
                        </button>
                    </div>
                </div>
                <div class="block-content block-content-full">
                    <table class="table table-vcenter table-light">
                        <tbody>
                            <tr>
                                <td class="text-center bg-white p-0" style="border-right:2px solid #ccc;">{{ __('Shipping Address') }}</td>
                                <td class="text-center bg-white p-0">{{ __('Billing Address') }}</td>
                            </tr>
                            <tr>
                                <td class="border-end">
                                    <div class="d-flex justify-content-center">
                                        {!! $order->shippingAddress->full_address_html !!}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        {!! $order->billingAddress?->full_address_html ?? $order->shippingAddress->full_address_html !!}
                                    </div>
                                </td>
                            </tr>
                            @foreach ($order->items as $item)
                            <tr>
                                <td class="ps-0">
                                    <a class="fw-semibold" href="javascript:void(0)">{{ $item->product->name }}</a>
                                    <div class="fs-sm text-muted">Quantity: {{ $item->quantity }}</div>
                                </td>
                                <td class="pe-0 fw-medium text-end">{{ AppHelper::calculate($item->price, $item->quantity) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tbody>
                            <tr>
                                <td class="ps-0 fw-medium">{{ __('Subtotal') }}</td>
                                <td class="pe-0 fw-medium text-end">{{ AppHelper::moneyWithSymbol($order->subtotal) }}</td>
                            </tr>
                            @settings('tax_enabled')
                            <tr>
                                <td class="ps-0 fw-medium">{{ __('Vat') }} ({{ AppSettings::get('tax_rate', 0) }}%)</td>
                                <td class="pe-0 fw-medium text-end">{{ AppHelper::moneyWithSymbol($order->tax) }}</td>
                            </tr>
                            @endsettings
                            <tr>
                                <td class="ps-0 fw-medium">{{ __('Discount') }}</td>
                                <td class="pe-0 fw-medium text-end">{{ AppHelper::moneyWithSymbol($order->discount) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-0 fw-medium">{{ __('Shipping Fee') }}</td>
                                <td class="pe-0 fw-medium text-end">{{ AppHelper::moneyWithSymbol($order->shipping) }}</td>
                            </tr>
                            <tr>
                                <td class="ps-0 fw-medium">{{ __('Total') }}</td>
                                <td class="pe-0 fw-bold text-end">{{ AppHelper::moneyWithSymbol($order->total) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


            <div class="text-center my-6">
                <h5 class="h5">{{ __('Thank you for shopping with us.') }}</h5>

                <div class="d-flex justify-content-center gap-3">
                    <a class="btn btn-alt-primary" href="{{ route('home') }}">
                        <i class="fas fa-arrow-left me-1"></i> {{ __('Back to Home') }}
                    </a>
                    <a class="btn btn-primary" href="{{ route('user.dashboard') }}">
                        <i class="fas fa-cubes me-1"></i> {{ __('Check Dashboard') }}
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    document.querySelectorAll('.copy-me').forEach(function(item) {
        item.addEventListener('click', function() {
            navigator.clipboard.writeText(item.dataset.info);
            item.dataset.bsOriginalTitle = "{{ __('Copied') }}";
            var tooltip = bootstrap.Tooltip.getInstance(item);
            tooltip.show();
            item.addEventListener('mouseleave', () => {
                tooltip.hide();
                item.dataset.bsOriginalTitle = "{{ __('Copy') }}";
            });
        });
    });
</script>
@endsection