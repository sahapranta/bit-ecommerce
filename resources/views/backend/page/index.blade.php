@extends('layouts.backend')

@section('content')
<div class="content">
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">
        Pages
      </h3>
      <div class="block-options">
        <a class="btn btn-sm btn-alt-primary" href="{{ route('admin.pages.create') }}">
          <i class="fas fa-plus me-1"></i>
          Add
        </a>
      </div>
    </div>
    <div class="block-content">
      <form action="{{ route('admin.pages.index') }}" method="get">
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
              <th>#</th>
              <th>Page</th>
              <th>Status</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach($pages as $page)
            <tr>
              <td>
                {{ $loop->iteration }}
              </td>
              <td>
                <a href="{{ route('admin.pages.edit', $page->id) }}">{{ $page->title }}</a>
              </td>
              <td>
                @if($page->is_active)
                <span class="badge bg-success">Active</span>
                @else
                <span class="badge bg-danger">Inactive</span>
                @endif
              </td>
              <td>
                <!-- <a href="{{ route('admin.pages.show', $page->id) }}" class="btn btn-sm btn-alt-primary me-1">View</a> -->
                <a href="{{ route('admin.pages.edit', $page->id) }}" class="btn btn-sm btn-alt-secondary me-1">
                  <i class="fas fa-edit"></i>
                  Edit
                </a>
                <button onclick="deleteItem(this, <?= $page->id ?>)" class="btn btn-sm btn-alt-danger">
                  <i class="fas fa-trash"></i>
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      {{ $pages->links() }}
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
    const url = "{{ route('admin.pages.destroy', ':id') }}";

    axios.delete(url.replace(':id', id))
      .then(function(response) {
        el.closest('tr').remove();
        toastr.success('Page deleted successfully');
      })
      .catch(function(error) {
        toastr.error(error?.response?.data?.message || 'Something went wrong!');
      });
  }
</script>
@endsection