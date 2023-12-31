@extends('frontend.user._layout')

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
                            @if($order->status->is('pending'))
                            <span class="badge bg-warning">Pending</span>
                            @elseif($order->status->is('processing'))
                            <span class="badge bg-info">Processing</span>
                            @elseif($order->status->is('delivered'))
                            <span class="badge bg-success">Delivered</span>
                            @elseif($order->status->is('returned'))
                            <span class="badge bg-danger">Returned</span>
                            @endif
                        </td>
                        <td>{{ AppHelper::moneyWithSymbol($order->total) }}</td>
                        <td>
                            <a href="{{ route('order.track', ['order'=>$order->order_id]) }}" class="btn btn-sm btn-primary">Track</a>
                            @if($order->status->is('pending'))
                            <a href="{{ route('user.order.cancel', $order->order_id) }}" class="btn btn-sm btn-alt-danger ms-1">Cancel</a>
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
</script>
@endpush