@extends('layouts.backend')

@section('content')

<div class="content">
  <div class="block block-rounded">
    <div class="block-header block-header-default">
      <h3 class="block-title">Notifications</h3>
      <div class="block-options">
        @if ($notifications->count() > 0)
        <a href="{{ route('user.notifications.mark-all-read') }}" class="btn btn-sm btn-alt-primary">
          <i class="fa fa-check"></i> Mark All as Read
        </a>
        <a href="{{ route('user.notifications.delete-all-read') }}" class="btn btn-sm btn-alt-danger">
          <i class="fa fa-trash"></i> Delete All
        </a>
        @endif
      </div>
    </div>
    <div class="block-content">
      <div class="table-responsive">
        <table class="table table-striped">
          <tbody>
            @forelse($notifications as $notification)
            <tr>
              <td>
                <i class="fas fa-circle text-muted fs-sm me-2"></i>
                <a href="{{ data_get($notification, 'data.action', '#') }}">{{ data_get($notification, 'data.message', 'Empty Message') }}</a>
                <span class="text-end">- {{ $notification->created_at->diffForHumans() }}</span>
              </td>
              <td>
                @if($notification->read_at)
                <span class="badge bg-success">Read</span>
                @else
                <span class="badge bg-danger">Unread</span>
                @endif
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="2" class="text-center py-4">😀 No notifications found</td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      {{ $notifications->links() }}
    </div>
  </div>
</div>
@endsection