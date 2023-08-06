@extends('frontend.user._layout')

@section('subcontent')


<div class="block block-rounded">
    <div class="block-header block-header-default">
        <h3 class="block-title">{{ __('Get Support') }}</h3>
    </div>
    <div class="block-content">
        @if ($supports->count() > 0)
        <div class="table-responsive mb-4">
            <table class="table table-striped table-bordered">
                <!-- <thead>
                    <tr>
                        <th># Description</th>
                    </tr>
                </thead> -->
                <tbody>
                    @foreach($supports as $support)
                    <tr>
                        <td class="px-0 pt-0">
                            <div class="bg-primary-light px-1 text-white fs-sm d-flex w-100 justify-content-between">
                                <div class="text-uppercase">Type: {{ $support->type }}</div>
                                <div>{{ $support->created_at->diffForHumans() }}</div>
                            </div>
                            <div class="fs-sm p-1 border" style="max-height:80px;overflow-y:auto;scrollbar-width:thin;">
                                <div>
                                    <span class="text-uppercase">Priority: {{ $support->priority }} , </span>
                                    <span class="text-uppercase">Status: </span>
                                    <span @class([ 'badge text-uppercase' , 'bg-info'=> $support->status == 'open',
                                    'bg-warning' => $support->status == 'working',
                                    'bg-success' => $support->status == 'resolved',
                                    'bg-danger' => $support->status == 'closed',
                                    ])>{{ $support->status }}</span>
                                </div>
                                {{ $support->description }}
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $supports->links() }}
        @endif
        <form action="{{ route('user.support') }}" method="post">
            @csrf
            <div class="mb-4">
                <label for="issue" class="form-label">Issue <span class="text-danger">*</span></label>
                <select class="form-select" id="issue" name="issue">
                    <option value="">Select an issue</option>
                    @foreach ([
                    'order',
                    'account',
                    'payment',
                    'delivery',
                    'refund',
                    'return',
                    'other',
                    ] as $type)
                    <option value="{{ $type }}">I have an issue with my {{$type}}</option>
                    @endforeach
                    <!-- <option value="7">I have an issue with my coupon</option> -->
                </select>
            </div>

            <div class="mb-4">
                <label for="description" class="form-label">Describe Your Issue <span class="text-danger">*</span></label>
                <textarea class="form-control" id="description" name="description" rows="3" placeholder="Enter your problem description"></textarea>
            </div>
            <div class="mb-4">
                <button type="submit" class="btn btn-alt-info">Submit</button>
            </div>
        </form>
    </div>
</div>


@endsection