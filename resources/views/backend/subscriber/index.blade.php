@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                Subscribers
            </h3>
            <div class="block-options">
                <a class="btn btn-sm btn-alt-primary" href="{{ route('admin.subscribers.create') }}">
                    <i class="fas fa-plus me-1"></i>
                    Add
                </a>
            </div>
        </div>
        <div class="block-content">
            <form action="{{ route('admin.subscribers.index') }}" method="get">
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
                            <th>Subscriber</th>
                            <th>Status</th>
                            <th>Active</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($subscribers as $subscriber)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>
                                <a data-info="{{ $subscriber->email }}" data-bs-toggle="tooltip" title="Copy Email"  class="copy-me" href="javascript:void(0)">{{ $subscriber->email }}</a>
                            </td>
                            <td>{{ $subscriber->status }}</td>
                            <td>
                                @if($subscriber->is_active)
                                <span class="badge bg-success">Active</span>
                                @else
                                <span class="badge bg-danger">Inactive</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.subscribers.edit', $subscriber->id) }}" class="btn btn-sm btn-alt-secondary me-1">Edit</a>
                                <button onclick="deleteItem(this, <?= $subscriber->id ?>)" class="btn btn-sm btn-alt-danger">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $subscribers->links() }}
        </div>
    </div>
</div>
@endsection

@section('js')
@include('backend.partials.swal')
@include('frontend.partials._copy-me')
<script>
    async function deleteItem(el, id) {
        if (!await confirmation()) {
            return;
        }
        const url = "{{ route('admin.subscribers.destroy', ':id') }}";

        axios.delete(url.replace(':id', id))
            .then(function(response) {
                el.closest('tr').remove();
                toastr.success('Subscriber deleted successfully');
            })
            .catch(function(error) {
                toastr.error(error?.response?.data?.message || 'Something went wrong!');
            });
    }
</script>
@endsection