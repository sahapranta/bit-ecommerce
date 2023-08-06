@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Add New Category
                    </h3>
                </div>
                <div class="block-content">
                    <form action="{{ route('admin.categories.store') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        <x-input name="name" label="Name" />

                        <x-input name="slug" label="Slug" info="Slug will be auto generated, if you left empty." />

                        <div class="mb-4 ">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">Select Parent Category</option>
                                @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('parent_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <x-input name="image" label="Logo" type="file" />

                        <div class="mb-4 d-flex py-3">
                            <a href="{{ route('admin.categories.index') }}" class="btn btn-alt-danger">
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