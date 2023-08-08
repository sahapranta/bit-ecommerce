@extends('frontend.user._layout')

@section('subcontent')

<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">Dashboard</h3>
    </div>
    <div class="block-content">

        @if ($user->unreadNotifications()->count() > 0)
        <div class="alert alert-info py-1">
            <div class="d-flex align-items-center justify-content-between">
                <div>You have {{ $user->unreadNotifications()->count() }} unread notifications.</div>
                <a href="{{ route('user.notifications') }}" class="btn btn-sm m-0">
                    <i class="fa fa-eye"></i> View All
                </a>
                <!-- <button type="button" style="padding:0.7rem 0;" class="btn-close px-2 fs-sm" data-bs-dismiss="alert" aria-label="Close"></button> -->
            </div>
        </div>
        @endif

        <div class="row push">
            <div class="col-md-6 col-xl-3">
                <a class="block block-rounded block-link-shadow bg-primary" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <div>
                            <i class="fa fa-2x fa-chart-area text-white-50"></i>
                        </div>
                        <dl class="ms-1 text-end mb-0">
                            <dt class="text-white h3 fw-extrabold mb-0">
                                {{ $summary->total_orders }}
                            </dt>
                            <dd class="text-white fs-sm fw-medium text-muted mb-0">
                                Orders
                            </dd>
                        </dl>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a class="block block-rounded block-link-shadow bg-success" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <div>
                            <i class="far fa-2x fa-star text-white-50"></i>
                        </div>
                        <dl class="ms-3 text-end mb-0">
                            <dt class="text-white h3 fw-extrabold mb-0">
                                {{ $reviews }}
                            </dt>
                            <dd class="text-white fs-sm fw-medium text-muted mb-0">
                                Reviews
                            </dd>
                        </dl>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a class="block block-rounded block-link-shadow bg-danger" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <dl class="me-3 mb-0">
                            <dt class="text-white h3 fw-extrabold mb-0">
                                {{ AppHelper::money(round($summary->total_expense)) }}
                            </dt>
                            <dd class="text-white fs-sm fw-medium text-muted mb-0">
                                Spent
                            </dd>
                        </dl>
                        <div>
                            <i class="fab fa-2x fa-bitcoin text-white-50"></i>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-md-6 col-xl-3">
                <a class="block block-rounded block-link-shadow bg-warning" href="javascript:void(0)">
                    <div class="block-content block-content-full d-flex align-items-center justify-content-between">
                        <dl class="me-3 mb-0">
                            <dt class="text-white h3 fw-extrabold mb-0">
                                {{ $summary->total_paid  }}
                            </dt>
                            <dd class="text-white fs-sm fw-medium text-muted mb-0">
                                Paid
                            </dd>
                        </dl>
                        <div>
                            <i class="fa fa-2x fa-boxes text-white-50"></i>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <div class="row push">
            <div class="col-md-12">
                <p class="text-uppercase border-bottom pb-2 text-muted"># {{ __('Recent Orders') }}</p>
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
                            @forelse($user->orders()->limit(5)->latest()->get() as $order)
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
                                    <a href="{{ route('checkout.confirm', $order->order_id) }}" class="btn btn-sm btn-warning">Pay</a>
                                    @else
                                    <a href="{{ route('order.track', ['order'=>$order->order_id]) }}" class="btn btn-sm btn-primary">Track</a>
                                    @endif
                                    @if($order->status->is('pending'))
                                    <button onclick="cancelOrder(event)" data-action="{{ route('user.order.cancel', $order->order_id) }}" class="btn btn-sm btn-alt-danger ms-1">Cancel</button>
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
    </div>
</div>

@endsection

@section('js')
<script defer src="{{ asset('js/plugins/sweetalert2/sweetalert2.min.js') }}"></script>
@include('frontend.partials._copy-me')
<script>
    function cancelOrder(event) {
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
@endsection