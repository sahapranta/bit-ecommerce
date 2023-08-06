@extends('layouts.simple')

@section('content')
<!-- Hero -->
<x-hero>

    <!-- <a href="{{ route('home') }}">
                <i class="fa fa-2x fa-circle-notch text-light"></i>
            </a> -->

    <h1 class="h2 text-white mb-2">
        <i class="fa fa-shopping-cart text-white-75 me-1"></i> {{ __('Checkout') }}
    </h1>
    <h2 class="h4 fw-normal text-white-75 mb-0">{{ __('Thank you for shopping from our store. Your items are almost at your doorstep.') }}</h2>
</x-hero>

<div class="content content-boxed content-full overflow-hidden">

    @if ($errors->any())
    <x-alert type="error" :message="$errors->first()" />
    @endif

    @if (session()->has('message'))
    <x-alert :type="session()->get('alert')" :message="session()->get('message')" />
    @endif

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf
        <div class="row">
            <div class="col-xl-7">
                <!-- Shipping Method -->
                <livewire:shipping-method />
                <!-- END Shipping Method -->
                <livewire:user-address />
                <!-- Payment -->
                <!-- <div class="block block-rounded block-mode-hidden">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            3. Payment
                        </h3>
                        <div class="block-options">
                            <button type="button" class="btn btn-sm btn-alt-secondary" data-toggle="block-option" data-action="content_toggle"></button>
                        </div>
                    </div>
                </div> -->
                <!-- Payment -->
            </div>
            <!-- Order Summary -->
            <div class="col-xl-5 order-xl-last">
                <livewire:checkout />
                <div class="mb-4">
                    <textarea name="delivery_note" class="form-control" placeholder="{{ __('Write Delivery Note Here') }}...">{{ old('delivery_note') }}</textarea>
                </div>
                <div class="mb-4">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" value="1" name="is_gift" id="is_gift" name="is_gift" @if(old('is_gift')) checked @endif>
                        <label class="form-check-label" for="is_gift">Mark as Gift</label>
                    </div>
                </div>

                <div class="mb-3 d-none">
                    <label class="form-label">Gift Message</label>
                    <textarea name="gift_message" id="gift_message" class="form-control" rows="2">{{ old('gift_message') }}</textarea>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-3 push">
                    <i class="fa fa-check opacity-50 me-1"></i>
                    {{ __('Complete Order') }}
                </button>
            </div>
            <!-- END Order Summary -->
        </div>
    </form>
    <!-- END Checkout -->
</div>
@endsection

@section('js')
<script src="{{ asset('js/lib/jquery.min.js') }}"></script>
<script>
    document.querySelector('#top-cart').classList.add('d-none');

    document.querySelectorAll('[data-action="content_toggle"]').forEach(btn => {
        btn.innerHTML = `<i class="fa-solid fa-chevron-${btn.closest('.block').classList.contains('block-mode-hidden') ? 'down' : 'up'}"> </i>`;
        btn.addEventListener('click', toggleBlock);
    });

    document.querySelectorAll('[data-action="state_toggle"]').forEach(btn => {
        btn.addEventListener('click', toggleBlockState);
    });

    async function toggleBlockState() {
        let block = $(this).closest('.block');
        block.toggleClass('block-mode-loading');
        setTimeout(() => {
            block.toggleClass('block-mode-loading')
            Livewire.emit('refreshCart');
        }, 1000);
    }

    function toggleBlock() {
        $(this).closest('.block').toggleClass('block-mode-hidden');
        $(this).find('i').toggleClass('fa-chevron-up').toggleClass('fa-chevron-down');
    }

    function showAddressForm() {
        $('#new_address_form').removeClass('d-none');
        $('#shipping_address_list').addClass('d-none');
    }

    function hideAddressForm() {
        $('#new_address_form').addClass('d-none');
        $('#shipping_address_list').removeClass('d-none');
    }


    var billingAddress = document.getElementById('billing-address');
    var billingAddressSame = document.getElementById('checkout-billing-address-same');

    billingAddressSame.addEventListener('change', function() {
        if (this.checked) {
            $(billingAddress).closest('.block').addClass('block-mode-hidden');
            // uncheck all billing address
            $('input[name="billing_address_id"]').each(function() {
                $(this).prop('checked', false);
            });
        } else {
            $(billingAddress).closest('.block').removeClass('block-mode-hidden');
            let shipping_address_id = $('input[name="shipping_address_id"]').val();
            $('input[name="billing_address_id"]').each(function() {
                if ($(this).val() !== shipping_address_id && $(this).val() !== '') {
                    $(this).prop('checked', true);
                }
            });
        }
    });

    document.querySelector('input[name="is_gift"]').addEventListener('change', function() {
        if (this.checked) {
            // select parent div
            document.querySelector('#gift_message').parentElement.classList.remove('d-none');
            document.querySelector('#gift_message').focus();
        } else {
            document.querySelector('#gift_message').parentElement.classList.add('d-none');
        }
    });

    $(document).ready(function() {
        $('button[type="submit"]').click(function(e) {
            e.preventDefault();
            // do validation or something here
            let icon = `<i class="fa fa-circle-notch fa-spin"></i> Processing...`;
            $(this).html(icon);
            $(this).attr('disabled', true);
            this.form.submit();
        });
    });
</script>
@endsection