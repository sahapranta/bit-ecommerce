@extends('layouts.backend')

@section('css')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">

@endsection

@section('content')
<!-- Page Content -->
<div class="content">
    <div class="row">
        <div class="col-md-7">
            <!-- Your Block -->
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Add Stock
                    </h3>
                </div>
                <div class="block-content">
                    <form action="{{ route('admin.products.stock.update') }}" method="post">
                        @csrf
                        <div class="mb-4">
                            <label class="form-label" for="product">Choose Product</label>
                            <select class="form-select js-select2 @error('product') is-invalid @enderror" id="product" name="product" data-placeholder="Choose a Product">
                                <option></option>
                            </select>
                            @error('product')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <x-input type="number" name="stock" label="Amount" placeholder="eg:500" min="0" step="1" />

                        <div class="mb-4">
                            <label class="form-label">Type</label>
                            <div class="space-x-2">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="add" id="increment" name="type" checked>
                                    <label class="form-check-label" for="increment">Add</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" value="remove" id="decrement" name="type">
                                    <label class="form-check-label" for="decrement">Deduct</label>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <button class="btn btn-success" type="submit">Update</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Low Stock Products
                    </h3>
                </div>
                <div class="block-content pt-0">
                    <table class="table table-striped table-hover table-vcenter">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Stock</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->stock }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="2" class="text-center">😀 No Low Stock Products</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $lowStockProducts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')
<script defer src="{{ asset('js/lib/jquery.min.js') }}"></script>
<script defer src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script>
    document.addEventListener("DOMContentLoaded", () => {
        $('.js-select2').select2({
            placeholder: 'Choose a product',
            ajax: {
                url: "{{ route('admin.products.search') }}",
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: params.term,
                        page: params.page || 1
                    }
                },
                processResults: function(data, params) {
                    params.page = params.page || 1;
                    return {
                        results: data.data,
                        pagination: {
                            more: data.current_page < data.last_page
                        }
                    };
                },
                cache: true
            },
            minimumInputLength: 1,
            templateResult: function(product) {
                if (product.loading) return "Loading...";
                return product.name + " - " + product.stock;
            },
            templateSelection: (product) => product.name,
        });

    });
</script>
@endsection