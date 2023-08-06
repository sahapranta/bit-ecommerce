@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                Support Ticket: <span class="badge bg-primary">{{ $support->type }}</span>
            </h3>
        </div>
        <div class="block-content">
            <form action="{{ route('admin.support.update', $support->id) }}" method="post">
                @csrf

                <table class="table table-striped table-sm">
                    <tr>
                        <th>ID</th>
                        <td>{{ $support->id }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>{{ $support->user->name }}, {{ $support->user->email }}</td>
                    </tr>
                    <tr>
                        <th>Type</th>
                        <td>{{ $support->type }}</td>
                    </tr>
                    <tr>
                        <th>Created</th>
                        <td>{{ $support->created_at->diffForHumans() }}</td>
                    </tr>
                </table>

                <div class="mb-4">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="4">{{ $support->description }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-4">
                        <label for="priority" class="form-label">Priority</label>
                        <select class="form-select" id="priority" name="priority">
                            <option value="">Select a priority</option>
                            @foreach ([
                            'low',
                            'medium',
                            'high',
                            'urgent',
                            ] as $priority)
                            <option value="{{ $priority }}" @if($support->priority == $priority) selected @endif>{{ ucfirst($priority) }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4 mb-4">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Select a status</option>
                            @foreach ([
                            'open',
                            'working',
                            'resolved',
                            'closed',
                            ] as $status)
                            <option value="{{ $status }}" @if($support->status == $status) selected @endif>{{ ucfirst($status) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mb-4 d-flex py-3">
                    <a href="{{ route('admin.support.index') }}" class="btn btn-alt-danger">
                        <i class="fas fa-arrow-left"></i>
                        Back to List
                    </a>

                    <button class="btn btn-success ms-2" type="submit">
                        <i class="fas fa-save"></i>
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection