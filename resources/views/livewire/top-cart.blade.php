<div class="dropdown flex-fill" id="top-cart">
    <button class="btn btn-outline-dark flex-fill" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <i class="fa fa-fw fa-shopping-cart me-1"></i>
        Cart
        <span class="badge bg-dark text-white ms-1 rounded-pill">{{ $count }}</span>
    </button>

    <!-- <div class="dropdown-menu dropdown-menu-md dropdown-menu-end p-0 border-0" > -->
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end-md block-rounded js-ecom-div-cart p-0" aria-labelledby="page-header-user-dropdown">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                <i class="fa fa-fw fa-shopping-cart text-muted me-1"></i>Cart ({{$count}})
            </h3>
            @if ($count > 0)
            <div class="block-options" wire:ignore>
                <button type="button" class="btn btn-xs px-2 btn-alt-warning" style="border-radius:0.25rem" data-bs-toggle="tooltip" data-bs-placement="left" title="This will clear your cart."
                     aria-label="Close" wire:click='clearCart'>
                    {{ __('Clear')  }}
                </button>
            </div>
            @endif
        </div>
        <div class="block-content py-0 px-0">
            <div data-simplebar style="max-height:50dvh;" id="cart-container">
                @forelse ($cart as $key=>$item)
                <div class="row align-items-center gx-0 justify-content-between px-2" wire:key="{{$key . '_item'}}">
                    <div class="col-2">
                        <a href="{{ route('product.view', $item->get('slug')) }}">
                            <img class="img-fluid" src="{{ AppHelper::placeholder($item->get('image')) }}" alt="">
                        </a>
                    </div>

                    <div class="col-10">
                        <d class="d-flex justify-content-between pt-2 ps-3">
                            <div> <a href="{{ route('product.view', $item->get('slug')) }}" class="h6">{{ $item->get('name') }}</a></div>
                            <div>
                                <button onclick="Livewire.emit('removeFromCart', <?= $item->get('product_id') ?>)" class="btn btn-sm text-alt-danger rounded-pill"><i class="fa fa-trash" data-bs-toggle="tooltip" data-bs-position="top" title="Remove Product"></i></button>
                            </div>
                        </d>
                        <div class="d-flex px-3">
                            <div class="text-muted me-3"><small>Qty: {{$item->get('quantity')}}</small></div>
                            <div class="text-nowrap">{{ AppHelper::calculate($item->get('price'), $item->get('quantity')) }}</div>
                        </div>
                    </div>
                    <hr class="mt-3 mb-1">
                </div>
                @empty
                <div class="text-center">
                    <img class="img-fluid" src="{{ asset('media/cart.webp') }}" alt="Empty Shopping Cart">
                </div>
                @endforelse
            </div>
            @if ($count > 0)
            <div class="d-flex justify-content-around align-items-center fw-semibold py-2 bg-light">
                <div>Total :</div>
                <div>{{ AppHelper::moneyWithSymbol($total) }}</div>
            </div>
            @endif
        </div>
        @if ($count > 0)
        <div class="p-2 bg-body-light text-end">
            <a class="btn btn-primary" href="{{ route('checkout') }}">
                Checkout
                <i class="fa fa-arrow-right opacity-50 ms-1"></i>
            </a>
        </div>
        @endif
    </div>
    <!-- </div> -->
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        let simple = document.querySelector('#cart-container');
        Livewire.on('cartUpdated', () => {
            let bar = new SimpleBar(simple, {
                autoHide: false
            });
            bar.recalculate();
        });
    });
</script>
@endpush