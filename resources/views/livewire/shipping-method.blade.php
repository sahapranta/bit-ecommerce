<div class="block block-rounded" wire:ignore>
    <div class="block-header block-header-default">
        <h3 class="block-title">
            1. Shipping Method
        </h3>
        <div class="block-options">
            <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="block-option" data-action="content_toggle"></button>
        </div>
    </div>
    <div class="block-content block-content-full space-y-3">
        @foreach ($shippingMethods as $key=> $method)
        <div class="form-check form-block" wire:key="{{ $key }}_delivery">
            <input type="radio" onchange="Livewire.emit('addShipping', '<?= $method['key'] ?>')" value="standard" class="form-check-input" id="checkout-delivery-{{$key}}" name="delivery" {{ $loop->first ? 'checked': ''}}>
            <label class="form-check-label" for="checkout-delivery-{{$key}}">
                <span class="d-block fw-normal p-1">
                    <span class="d-block fw-semibold mb-1">{{ $method['name'] }}</span>
                    <span class="d-block fs-sm fw-medium text-muted"><span class="fw-semibold">{{ $method['fee']>0 ? '+' . AppHelper::money($method['fee']) : 'FREE' }}</span> {{ $method['description'] }}</span>
                </span>
            </label>
        </div>
        @endforeach
    </div>
</div>