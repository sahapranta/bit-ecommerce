@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                Categories
            </h3>
            <div class="block-options">
                <a class="btn btn-sm btn-alt-primary" href="{{ route('admin.categories.create') }}">
                    <i class="fas fa-plus me-1"></i>
                    Add
                </a>
            </div>
        </div>
        <div class="block-content">
            <form action="{{ route('admin.categories.index') }}" method="get">
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
                            <th>Category</th>
                            <th>Products</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($categories as $category)
                        <tr>
                            <td>
                                <img src="{{ $category->image ?: AppHelper::placeholder() }}" alt="{{ $category->name }}" class="img-fluid rounded-circle" width="32px">
                            </td>
                            <td>
                                <a href="{{ route('admin.categories.edit', $category->id) }}">{{ $category->name }}</a>
                            </td>
                            <td>{{ $category->products_count }}</td>
                            <td>
                                <!-- <a href="{{ route('admin.categories.show', $category->id) }}" class="btn btn-sm btn-alt-primary me-1">View</a> -->
                                <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-sm btn-alt-secondary me-1">Edit</a>
                                <button onclick="deleteItem(this, <?= $category->id ?>)" class="btn btn-sm btn-alt-danger">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $categories->links() }}
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
        const url = "{{ route('admin.categories.destroy', ':id') }}";

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