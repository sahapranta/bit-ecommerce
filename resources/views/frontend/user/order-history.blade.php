@extends('frontend.user._layout')

@push('styles')
    <link rel="stylesheet" href="{{ asset('js/plugins/sweetalert2/sweetalert2.min.css') }}">
@endpush

@section('subcontent')

<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">Orders</h3>
    </div>
    <div class="block-content">
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            {{ $loop->iteration }}
                        </td>
                        <td>{{ $order->created_at->format('d-m-Y') }}</td>
                        <td>{{ $order->items->count() }}</td>
                        <td>
                            <span class="badge text-capitalize bg-{{ $order->status->getColor() }}">{{$order->status->value}}</span>
                        </td>
                        <td>{{ AppHelper::moneyWithSymbol($order->total) }} ({{ AppHelper::withBtcSymbol($order->btc_total) }})</td>
                        <td>
                            @if (!$order->is_paid)
                            <a href="{{ route('checkout.confirm', $order->order_id) }}" class="btn btn-sm btn-warning" >Pay</a>
                            @else
                            <a href="{{ route('order.track', ['order'=>$order->order_id]) }}" class="btn btn-sm btn-primary">Track</a>
                            @endif
                            @if($order->status->is('pending'))
                            <button onclick="cancelOrder()" data-action="{{ route('user.order.cancel', $order->order_id) }}" class="btn btn-sm btn-alt-danger ms-1">Cancel</button>
                            @elseif($order->is_paid)
                            <a href="{{ route('checkout.invoice', $order->order_id) }}" class="btn btn-sm btn-alt-primary ms-1">Invoice</a>
                            @endif
                            <button class="btn btn-sm btn-alt-secondary copy-me" data-info="{{ $order->order_id }}" data-bs-toggle="tooltip" title="Copy Order ID">
                                <i class="fas fa-copy"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">😀 No orders found</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script defer src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
<script>
    document.querySelectorAll('.copy-me').forEach(function(item) {
        item.addEventListener('click', function() {
            navigator.clipboard.writeText(item.dataset.info);
            let title = item.dataset.bsOriginalTitle;
            item.dataset.bsOriginalTitle = "{{ __('Copied') }}";
            var tooltip = bootstrap.Tooltip.getInstance(item);
            tooltip.show();
            item.addEventListener('mouseleave', () => {
                tooltip.hide();
                item.dataset.bsOriginalTitle = title;
            });
        });
    });


    function cancelOrder() {
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: "{{ __('Yes, cancel it!') }}",
            cancelButtonText: "{{ __('No, keep it') }}",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                let action = event.target.dataset.action;
                axios.post(action)
                    .then(function(response) {
                        Swal.fire(
                            'Cancelled!',
                            response.data.message,
                            'success'
                        ).then((result) => location.reload());
                    })
                    .catch(function(error) {
                        Swal.fire(
                            'Cancelled!',
                            error.response.data.message,
                            'error'
                        );
                    });
            }
        })
    }
</script>
@endpush