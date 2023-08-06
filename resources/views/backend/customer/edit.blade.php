@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Update Customer
                    </h3>
                </div>
                <div class="block-content pb-3">
                    <form action="{{ route('admin.customers.update', $customer->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <x-input name="name" label="Name" value="{{ $customer->name }}" />

                        <x-input name="email" label="Email" value="{{ $customer->email }}" />


                        <div class="mb-4 d-flex py-3">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-alt-danger">
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
    </div>
</div>
@endsection