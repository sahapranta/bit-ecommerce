@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                Support Tickets
            </h3>
        </div>
        <div class="block-content">
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>User</th>
                            <th>Type</th>
                            <th>Description</th>
                            <th>Status</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($supports as $support)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $support->user->name }}</td>
                            <td>{{ $support->type }}</td>
                            <td width="40%" style="max-height: 100px;" data-simplebar>{{ $support->description }}</td>
                            <td>
                                <span @class([ 'badge text-uppercase' , 'bg-info'=> $support->status == 'open',
                                    'bg-warning' => $support->status == 'working',
                                    'bg-success' => $support->status == 'resolved',
                                    'bg-danger' => $support->status == 'closed',
                                    ])>{{ $support->status }}</span>
                            </td>
                            <td>
                                <a href="{{ route('admin.support.edit', $support->id) }}" class="btn btn-sm btn-alt-secondary me-1">Edit</a>
                                <button onclick="deleteItem(this, <?= $support->id ?>)" class="btn btn-sm btn-alt-danger">Delete</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $supports->links() }}
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
        const url = "{{ route('admin.support.destroy', ':id') }}";

        axios.delete(url.replace(':id', id))
            .then(function(response) {
                el.closest('tr').remove();
                toastr.success('Support Ticket deleted successfully');
            })
            .catch(function(error) {
                toastr.error(error?.response?.data?.message || 'Something went wrong!');
            });
    }
</script>
@endsection