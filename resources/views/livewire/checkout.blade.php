<div class="block block-rounded block-mode-loading-refresh">
    <div class="block-header">
        <h3 class="block-title">
            Order Summary
        </h3>
        <div class="block-options">
            <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="block-option" data-action="state_toggle">
                <i class="fa fa-refresh"></i>
            </button>
        </div>
    </div>
    <div class="block-content block-content-full">
        <table class="table table-vcenter">
            <tbody>
                @foreach ($cart as $key => $item)
                <tr wire:key="{{ $key }}_cart_item">
                    <td class="ps-0">
                        <a class="fw-semibold" href="{{ route('product.view', $item['slug']) }}">{{ $item['name'] }}</a>
                        <div class="fs-sm text-muted d-flex align-items-center gap-2 mt-1">
                            Quantity:
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <button class="btn btn-alt-danger btn-xs border-radius-left" type="button" wire:click="decrement('{{ $item['product_id'] }}')" {{ $item['quantity'] > 1 ? '' : 'disabled' }}>
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                                <input type="text" readonly value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}" class="form-control form-control-alt py-0 px-2" style="max-width: 40px;border-radius:0%;user-select:none;pointer-events:none;" />
                                <div class="input-group-append">
                                    <button class="btn btn-xs btn-alt-success border-radius-right" type="button" wire:click="increment('{{ $item['product_id'] }}')" {{ $item['quantity'] < $item['stock'] ? '' : 'disabled' }}>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </td>
                    <td class="pe-0 fw-medium text-end">{{ AppHelper::calculate($item['price'], $item['quantity']) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tbody>
                <tr>
                    <td class="ps-0 fw-medium">{{ __('Subtotal') }}</td>
                    <td class="pe-0 fw-medium text-end">{{ AppHelper::moneyWithSymbol($subtotal) }}</td>
                </tr>
                @settings('tax_enabled')
                <tr>
                    <td class="ps-0 fw-medium">{{ __('Tax') }} ({{ AppSettings::get('tax_rate', 0) }}%)</td>
                    <td class="pe-0 fw-medium text-end">{{ AppHelper::moneyWithSymbol($tax) }}</td>
                </tr>
                @endsettings
                <tr>
                    <td class="ps-0 fw-medium">{{ __('Discount') }}</td>
                    <td class="pe-0 fw-medium text-end">{{ AppHelper::moneyWithSymbol($discount) }}</td>
                </tr>
                <tr>
                    <td class="ps-0 fw-medium">{{ __('Shipping') }}</td>
                    <td class="pe-0 fw-medium text-end">{{ AppHelper::moneyWithSymbol($shipping) }}</td>
                </tr>
                <tr>
                    <td class="ps-0 fw-medium">{{ __('Total') }}</td>
                    <td class="pe-0 fw-bold text-end">{{ AppHelper::moneyWithSymbol($total) }}</td>
                </tr>
                <tr>
                    <td class="ps-0 fw-medium">{{ __('TOTAL IN BTC') }}</td>
                    <td class="pe-0 fw-bold text-end">{{ AppHelper::convertToBTC($total) }}</td>
                </tr>
            </tbody>
        </table>
        <small> <i class="fas fa-info-circle"></i> Total may vary depending on <b>BITCOIN CURRENT PRICE</b>.
        <br> (1 BTC = {{ AppHelper::getBTCRate() }} GBP) </small>
    </div>
</div>