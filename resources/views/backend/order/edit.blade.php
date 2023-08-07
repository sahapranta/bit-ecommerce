@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                Edit #{{$order->tracking_id}}
            </h3>
        </div>
        <div class="block-content">
            <form action="{{ route('admin.orders.update', $order->id) }}" method="post">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <div class="btn-group">
                            <button type="button" class="btn btn-light disabled">
                                <span class="fw-bold">ORDER ID:</span> {{ $order->order_id }}
                            </button>
                            <button type="button" class="btn btn-alt-info copy-me" data-info="{{ $order->order_id }}" data-bs-toggle="tooltip" title="Copy">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="form-group">
                            <label class="form-label" for="status">ORDER STATUS</label>
                            <select name="status" class="form-select bg-light">
                                <option value="">STATUS</option>
                                @foreach(\App\Enums\OrderStatusEnum::options() as $key => $status)
                                <option value="{{ $status }}" <?= $order->status->value == $status ? 'selected' : '' ?>>{{ $key }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <!-- 'delivery_method',
                    'delivery_status',
                    'delivery_date',
                    'delivery_note', -->


                    <div class="col-md-4 mb-3">
                        @php
                        $methods = AppSettings::get('delivery_methods', ['self_pickup', 'home_delivery']);
                        @endphp
                        <label class="form-label" for="delivery_method">DELIVERY METHOD</label>
                        <select name="delivery_method" class="form-select bg-light">
                            <option value="">DELIVERY METHOD</option>
                            @foreach($methods as $method => $value)
                            <option value="{{ $method }}" <?= old('delivery_method', $order->delivery_method) == $method ? 'selected' : '' ?>>{{ strtoupper($method) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        @php
                        $delivery_status = [
                        'processing' => 'Processing',
                        'shipped' => 'Shipped',
                        'delivered' => 'Delivered',
                        'cancelled' => 'Cancelled',
                        'returned' => 'Returned',
                        'refunded' => 'Refunded',
                        ];
                        @endphp
                        <label class="form-label" for="delivery_status">DELIVERY STATUS</label>
                        <select name="delivery_status" class="form-select bg-light">
                            <option value="">CHOOSE STATUS</option>
                            @foreach($delivery_status as $key=> $method)
                            <option value="{{ $key }}" <?= old('delivery_status', $order->delivery_status) == $method ? 'selected' : '' ?>>{{ strtoupper($method) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">DELIVERY DATE</label>
                        <input type="date" class="form-control" value="{{ $order->delivery_date }}" name="delivery_date" id="delivery_date">
                    </div>



                    <div class="col-md-4 mb-3">
                        <label class="form-label">PAYMENT STATUS</label>
                        <input type="text" class="form-control" value="{{ $order->payment_status }}" name="payment_status" id="payment_status">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label class="form-label">PAYMENT METHOD</label>
                        <input type="text" class="form-control" value="{{ $order->payment_method }}" name="payment_method" id="payment_method">
                    </div>

                    <div class="col-md-8 mb-3">
                        <label class="form-label">DELIVERY NOTE</label>
                        <textarea name="delivery_note" id="delivery_note" class="form-control" rows="2">{{ $order->delivery_note }}</textarea>
                    </div>

                    <div class="col-md-12 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" value="1" name="is_gift" id="is_gift" name="is_gift" @if($order->is_gift) checked @endif>
                            <label class="form-check-label" for="is_gift">IS GIFT</label>
                        </div>
                    </div>

                    <div class="col-md-8 mb-3 @if(!$order->gift_message) d-none @endif">
                        <label class="form-label">GIFT MESSAGE</label>
                        <textarea name="gift_message" id="gift_message" class="form-control" rows="2">{{ $order->gift_message }}</textarea>
                    </div>


                    <div class="col-md-12 my-4">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-danger">
                            <i class="fas fa-times me-1"></i> CANCEL
                        </a>
                        <button type="submit" class="btn btn-primary ms-2">
                            <i class="fas fa-save me-1"></i> UPDATE
                        </button>


                    </div>
                </div>
            </form>
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

    document.querySelector('input[name="is_gift"]').addEventListener('change', function() {
        if (this.checked) {
            document.querySelector('#gift_message').closest('.col-md-8').classList.remove('d-none');
            document.querySelector('#gift_message').focus();
        } else {
            document.querySelector('#gift_message').closest('.col-md-8').classList.add('d-none');
        }
    });
</script>
@endsection