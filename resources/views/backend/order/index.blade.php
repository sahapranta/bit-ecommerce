@extends('layouts.backend')

@section('css')

@endsection

@section('content')
<div class="content">
    <div class="row">
        @foreach ($options as $option)
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="{{ route('admin.orders.index', ['status'=> $option['name']]) }}">
                <div class="block-content block-content-full">
                    <div class="fs-2 fw-semibold text-{{ $option['type'] }}">
                        {{ $option['value'] }}
                    </div>
                </div>
                <div class="block-content py-2 bg-body-light">
                    <p class="fw-medium fs-sm text-{{ $option['type'] }} mb-0">
                        {{ $option['title'] }}
                    </p>
                </div>
            </a>
        </div>
        @endforeach
    </div>
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                Orders
            </h3>
        </div>
        <div class="block-content">
            <form action="{{ route('admin.orders.index') }}">
                <div class="row mb-4 gx-2">
                    <div class="col-md-2 ms-auto">
                        <div class="form-group">
                            <select name="status" class="form-control-alt form-select">
                                <option value="">Status</option>
                                @foreach(\App\Enums\OrderStatusEnum::options() as $status)
                                <option value="{{ $status }}" <?= request('status') == $status ? 'selected' : '' ?>>{{ strtoupper($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <div class="input-group">
                            <input type="search" class="form-control form-control-alt" id="products-search" name="search" placeholder="Search orders by user.." value="{{ request('search') }}">
                            <span class="input-group-text bg-body border-0">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-info">Filter</button>
                            <a href="{{ route('admin.orders.index') }}" class="btn btn-alt-info"><i class="fas fa-times"></i></a>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-borderless table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th class="d-none d-sm-table-cell">Date</th>
                            <th>Status</th>
                            <th>Total</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td class="fw-semibold">
                                {{ $order->user->name }}
                            </td>
                            <td class="text-center">
                                {{ $order->items->sum('quantity') }}
                            </td>
                            <td class="d-none d-sm-table-cell">
                                {{ $order->created_at->diffForHumans() }}
                            </td>
                            <td>
                                <span
                                @class([
                                    'badge text-uppercase',
                                    'bg-success' => $order->status->value == 'completed',
                                    'bg-warning' => $order->status->value == 'processing',
                                    'bg-danger' => $order->status->value == 'cancelled',
                                    'bg-info' => $order->status->value == 'pending',
                                    'bg-dark' => $order->status->value == 'failed',
                                ])
                                >
                                    {{ $order->status->value }}
                                </span>
                            </td>
                            <td class="d-none d-sm-table-cell">
                                {{ AppHelper::money($order->total) }}
                            </td>
                            <td>
                                <a class="btn btn-sm btn-alt-success" href="{{ route('admin.orders.show', $order->id) }}">
                                    <i class="fa fa-eye"></i> View
                                </a>
                                <a class="btn btn-sm btn-alt-primary" href="{{ route('admin.orders.edit', $order->id) }}">
                                    <i class="fa fa-pencil-alt"></i> Edit
                                </a>
                                <a class="btn btn-sm btn-alt-danger" data-bs-toggle="tooltip" title="Delete" onclick="deleteRow(this, '{{ $order->id }}')">
                                    <i class="fa fa-trash"></i> Delete
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center">😀 No orders found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
@include('backend.partials.swal')
<script>
    async function deleteRow(el, id) {
        if (!await confirmation()) {
            return;
        }
        let url = "{{ route('admin.orders.destroy', ':id') }}";
        axios.delete(url.replace(':id', id))
            .then((response) => {
                toastr.success(response.data.message);
                el.closest('tr').remove();
            })
            .catch((error) => {
                // handle error
                toastr.error(error.response?.data?.message || 'Something went wrong!');
            })
    }
</script>
@endsection