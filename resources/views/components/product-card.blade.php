@props(['product'])

<div class="block block-rounded h-100 mb-0">
    <div class="block-content p-1 ribbon ribbon-danger">
        @if (ceil($product->discount) > 0)
        <div class="ribbon-box">{{ AppHelper::getPercentSaved($product->discount, $product->price, 1) }}%</div>
        @endif
        <div class="options-container" tabindex="0">
            <img class="img-fluid options-item" onerror="this.onerror=null;this.src='<?= asset('media/placeholder.webp') ?>'" src="{{ $product->image }}" alt="{{ $product->name }}">
            <div class="options-overlay bg-black-75">
                <div class="options-overlay-content">
                    <a class="btn btn-sm btn-alt-secondary" href="{{ route('product.view', $product->slug) }}">
                        View
                    </a>
                    <a class="btn btn-sm btn-alt-secondary" href="javascript:void(0)" onclick="Livewire.emit('addToCart', '<?= $product->slug ?>')">
                        <i class="fa fa-plus text-success me-1"></i> Add to cart
                    </a>
                    @if ($product->ratings > 0)
                    <x-star-rating :rating="$product->ratings" class="mt-3" />
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="block-content">
        <div class="mb-1">
            <div class="fw-semibold float-end ms-1">
                {{ AppHelper::moneyWithSymbol($product->discounted_price) }} <br>
                <small class="text-muted">{{ AppHelper::convertToBTC($product->discounted_price) }}</small>
            </div>
            <a class="h6" href="{{ route('product.view', $product->slug) }}">{{ $product->name }}</a>
        </div>
        <p class="fs-sm text-muted">{{ $product->title }}</p>
    </div>
</div>