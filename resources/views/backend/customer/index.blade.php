@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                Customers
            </h3>
            <div class="block-options">
                <a class="btn btn-sm btn-alt-info" href="{{ route('admin.customers.create') }}">
                    <i class="fas fa-plus me-1"></i>
                    Add
                </a>
            </div>
        </div>
        <div class="block-content">
            <form action="{{ route('admin.customers.index') }}" method="get">
                <div class="row mb-3">
                    <div class="col-md-6 ms-auto">
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Search...">
                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th></th>
                            <th>Customer</th>
                            <th>Orders</th>
                            <th>Spent</th>
                            <th>Last Order</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $customer)
                        <tr>
                            <td>
                                <img src="{{ $customer->avatar }}" alt="" class="img-fluid rounded-circle" width="32px">
                            </td>
                            <td>
                                <a href="{{ route('admin.customers.edit', $customer->id) }}">{{ $customer->name }}</a>
                            </td>
                            <td>{{ $customer->orders_count }}</td>
                            <td>{{ AppHelper::money($customer->orders_sum_total) }}</td>
                            <td>{{ $customer->order?->created_at->diffForHumans() }}</td>
                            <td>
                                <a href="{{ route('admin.customers.edit', $customer->id) }}" class="btn btn-sm btn-alt-secondary me-1">
                                    <i class="fas fa-edit"></i>
                                    Edit
                                </a>
                                <button onclick="deleteItem(this, <?= $customer->id ?>)" class="btn btn-sm btn-alt-danger me-1">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <a href="{{ route('impersonate', $customer->id) }}" class="btn btn-sm btn-alt-warning" data-bs-toggle="tooltip" title="Login as {{ $customer->name }}">
                                    <i class="fas fa-sign-in me-1"></i>
                                    Impersonate
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $customers->links() }}
        </div>
    </div>
</div>
@endsection

@section('js')
@include('backend.partials.swal')
<script>
    async function deleteItem(el, id) {
        if (!await confirmation()) {
            return;
        }
        const url = "{{ route('admin.customers.destroy', ':id') }}";

        axios.delete(url.replace(':id', id))
            .then(function(response) {
                el.closest('tr').remove();
                toastr.success('Category deleted successfully');
            })
            .catch(function(error) {
                toastr.error(error?.response?.data?.message || 'Something went wrong!');
            });
    }
</script>
@endsection