@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Add New Customer
                    </h3>
                </div>
                <div class="block-content text-center pb-3">
                    <h3 class="h3">Need to sign up as user first.</h3>

                    <a href="{{ route('register') }}" class="btn btn-primary me-2" target="_blank">
                        <i class="fas fa-user-plus"></i>
                        Sign Up
                    </a>
                    <a href="{{ route('admin.customers.index') }}" class="btn btn-alt-danger">
                        <i class="fas fa-arrow-left"></i>
                        Back to List
                    </a>

                    <hr>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection