@extends('layouts.backend')

@section('content')
<div class="content">
    <!-- Quick Overview -->
    <div class="row">
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="{{ route('admin.products.create') }}" data-bs-toggle="tooltip" title="{{ __('Add New Product') }}">
                <div class="block-content block-content-full">
                    <div class="fs-2 fw-semibold text-success">
                        <i class="fa fa-plus"></i>
                    </div>
                </div>
                <div class="block-content py-2 bg-body-light">
                    <p class="fw-medium fs-sm text-success mb-0">
                        {{ __('Add New Product') }}
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="fs-2 fw-semibold text-danger">{{ $summary->out_of_stock }}</div>
                </div>
                <div class="block-content py-2 bg-body-light">
                    <p class="fw-medium fs-sm text-danger mb-0">
                        {{ __('Out of Stock') }}
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="fs-2 fw-semibold text-info">{{ $summary->new_product ?? 0 }}</div>
                </div>
                <div class="block-content py-2 bg-body-light">
                    <p class="fw-medium fs-sm text-info mb-0">
                        {{__('New Product') }}
                    </p>
                </div>
            </a>
        </div>
        <div class="col-6 col-lg-3">
            <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
                <div class="block-content block-content-full">
                    <div class="fs-2 fw-semibold text-dark">{{ $summary->stock }}</div>
                </div>
                <div class="block-content py-2 bg-body-light">
                    <p class="fw-medium fs-sm text-muted mb-0">
                        {{ __('All Products') }}
                    </p>
                </div>
            </a>
        </div>
    </div>
    <!-- END Quick Overview -->

    <!-- All Products -->
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title"> {{ __('All Products') }}</h3>
            <div class="block-options">
                <div class="dropdown">
                    <button type="button" class="btn-block-option" id="dropdown-ecom-filters" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        {{ __('Filters') }} <i class="fa fa-angle-down ms-1"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdown-ecom-filters">
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('admin.products.index', ['order'=>'new']) }}">
                            {{ __('New') }}
                            <span class="badge bg-info rounded-pill">{{ $summary->new_product }}</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('admin.products.index', ['order'=>'out']) }}">
                            {{ __('Out of Stock') }}
                            <span class="badge bg-danger rounded-pill">{{ $summary->out_of_stock }}</span>
                        </a>
                        <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('admin.products.index') }}">
                            {{ __('All') }}
                            <span class="badge bg-primary rounded-pill">{{ $summary->stock }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="block-content">
            <!-- Search Form -->
            <form action="{{ route('admin.products.index') }}">
                <div class="row mb-4 gx-2">
                    <div class="col-md-2 ms-auto">
                        <div class="form-group">
                            <select name="status" class="form-control-alt form-select">
                                <option value="">Status</option>
                                @foreach(['draft', 'published', 'archived'] as $status)
                                <option value="{{ $status }}" <?= request('status') == $status ? 'selected' : '' ?>>{{ strtoupper($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <select name="category" class="form-control-alt form-select">
                                <option value="">Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" <?= request('category') == $category->id ? 'selected' : '' ?>>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 d-flex gap-2">
                        <div class="input-group">
                            <input type="search" class="form-control form-control-alt" id="products-search" name="search" placeholder="Search all products.." value="{{ request('search') }}">
                            <span class="input-group-text bg-body border-0">
                                <i class="fa fa-search"></i>
                            </span>
                        </div>
                        <div class="btn-group">
                            <button type="submit" class="btn btn-info">Filter</button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-alt-danger"><i class="fas fa-times"></i></a>
                        </div>
                    </div>
                </div>
            </form>


            <!-- All Products Table -->
            <div class="table-responsive">
                <table class="table table-borderless table-striped table-vcenter">
                    <thead>
                        <tr>
                            <th class="text-center" style="width: 100px;">ID</th>
                            <!-- <th class="d-none d-md-table-cell">UPC</th> -->
                            <th class="">Name</th>
                            <th class="d-none d-sm-table-cell text-center">Added</th>
                            <th>Status</th>
                            <th class="d-none d-sm-table-cell text-end">Price</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                        <tr>
                            <td class="text-center fs-sm">
                                <a href="{{ route('admin.products.edit', $product->id) }}">#{{$product->id}}</a>
                            </td>
                            <!-- <td class="d-none d-md-table-cell fs-sm">
                                <a class="fw-semibold" href="{{ route('admin.products.edit', $product->id) }}">
                                    <small>#{{ $product->upc }}</small>
                                </a>
                            </td> -->
                            <td class="fs-sm">{{ $product->name }}</td>
                            <td class="d-none d-sm-table-cell text-center fs-sm">{{ $product->created_at->diffForHumans() }}</td>
                            <td>
                                @if ($product->stock > 0)
                                <span class="badge bg-success">Available</span>
                                @else
                                <span class="badge bg-danger">Out of Stock</span>
                                @endif
                            </td>
                            <td class="text-end d-none d-sm-table-cell fs-sm">
                                <strong>{{ AppHelper::money($product->price) }}</strong>
                            </td>
                            <td class="text-center fs-sm">
                                <a class="btn btn-sm btn-alt-secondary" href="{{ route('admin.products.edit', $product->id) }}" data-bs-toggle="tooltip" title="View">
                                    <i class="fa fa-fw fa-eye"></i>
                                </a>
                                <button class="btn btn-sm btn-alt-danger" data-bs-toggle="tooltip" title="Delete" onclick="deleteProduct(this, '{{ $product->id }}')">
                                    <i class="fa fa-fw fa-times text-danger"></i>
                                </button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4">😀 No Products Found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{$products->links()}}

        </div>
    </div>

</div>
@endsection

@section('js')
@include('backend.partials.swal')
<script>
    async function deleteProduct(target, id) {
        if (!await confirmation()) {
            return;
        }
        const url = "{{ route('admin.products.destroy', ':id') }}";
        try {
            const {
                data
            } = await axios.delete(url.replace(':id', id));
            if (data?.success === true) {
                toastr.success(data?.message);
                bootstrap.Tooltip.getInstance(target).hide();
                target.closest('tr').remove();
            } else {
                toastr.error(data?.message || 'Something went wrong!');
            }
        } catch (error) {
            toastr.error(error?.response?.data?.message || 'Something went wrong!');
        }
    }
</script>
@endsection