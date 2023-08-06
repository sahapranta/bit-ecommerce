@extends('layouts.backend')

@section('css')
<style>
    .ck-editor__editable[role="textbox"] {
        /* editing area */
        min-height: 300px;
    }

    /* .ck-content .image {
        max-width: 80%;
        margin: 20px auto;
    } */
</style>
@endsection

@section('content')
<div class="content">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">
                Edit Page
            </h3>
        </div>
        <div class="block-content">
            <form action="{{ route('admin.pages.update', $page->id) }}" method="post">
                @csrf
                @method('PUT')
                <x-input name="title" :value="$page->title" required/>
                <x-input name="slug" info="Slug will be auto generated, if you left empty." :value="$page->slug">
                    <a class="fs-sm text-secondary" href="{{ route('pages.show', $page->slug) }}" target="_blank">eg: <span class="text-warning">{{ route('pages.show', $page->slug) }}</span></a>
                </x-input>

                <x-input name="subtitle" :value="$page->subtitle" />

                <div class="mb-4">
                    <label for="content" class="form-label">Content</label>
                    <textarea name="body" id="content" class="form-control" rows="10">{{ old('body', $page->body) }}</textarea>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-4">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" value="1" id="is_active" name="is_active" @if($page->is_active) checked @endif >
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-4">
                            <label for="meta_description" class="form-label">Meta Description</label>
                            <textarea placeholder="Short description for better SEO..." name="meta_description" id="meta_description" class="js-maxlength form-control" maxlength="120" data-always-show="true" data-placement="top" rows="3">{{ old('meta_description', $page->meta_description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="mb-4 d-flex py-3">
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-alt-danger">
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


@section('js')
<script defer src="{{ asset('js/lib/jquery.min.js') }}"></script>
<script defer src="{{ asset('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
@include('backend.partials.ckeditor')
<script>
    window.onload = () => {
        initEditor(document.querySelector('#content'));
        One.helpers(['jq-maxlength']);
    }
</script>
@endsection