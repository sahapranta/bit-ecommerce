@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Add New Subscriber
                    </h3>
                </div>
                <div class="block-content">
                    <form action="{{ route('admin.subscribers.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <x-input name="email" required type="email" />
                        <x-input name="name" />
                        <div class="d-flex gap-3">
                            <x-input name="phone" />
                            <x-input name="status" />
                            <x-input name="group" />
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" checked>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>

                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" id="is_verified" name="is_verified" checked>
                                <label class="form-check-label" for="is_verified">Verified</label>
                            </div>
                        </div>

                        <div class="mb-4 d-flex py-3">
                            <a href="{{ route('admin.subscribers.index') }}" class="btn btn-alt-danger">
                                <i class="fas fa-arrow-left"></i>
                                Back to List
                            </a>

                            <button class="btn btn-success ms-2" type="submit">
                                <i class="fas fa-save"></i>
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection