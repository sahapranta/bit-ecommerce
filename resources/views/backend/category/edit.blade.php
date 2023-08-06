@extends('layouts.backend')

@section('content')
<div class="content">
    <div class="row">
        <div class="col-md-8 offset-2">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        Edit Category
                    </h3>
                </div>
                <div class="block-content">
                    <form action="{{ route('admin.categories.update', $category->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <x-input name="name" label="Name" value="{{ $category->name }}" />

                        <x-input name="slug" label="Slug" value="{{ $category->slug }}" info="Slug will be auto generated, if you left empty." />

                        <div class="mb-4 ">
                            <label for="parent_id" class="form-label">Parent Category</label>
                            <select name="parent_id" id="parent_id" class="form-select">
                                <option value="">Select Parent Category</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('parent_id', $category->parent_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-3">
                                <div class="mb-4 border border-2 text-center py-4 rounded-circle shadow">
                                    <img src="{{ $category->image }}" alt="{{ $category->name }} Logo" class="img-fluid" style="max-width: 70px;">
                                </div>
                            </div>
                            <div class="col-9">
                                <x-input name="image" label="Logo" type="file" />
                            </div>
                        </div>

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