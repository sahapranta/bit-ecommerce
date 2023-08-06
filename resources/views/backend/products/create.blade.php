@extends('layouts.backend')

@section('css')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/dropzone/min/dropzone.min.css') }}">
<style>
    .dropzone .dz-preview.dz-image-preview {
        background: transparent !important;
    }

    .ck-editor__editable[role="textbox"] {
        min-height: 300px;
    }
</style>
@endsection


@section('content')
<div class="bg-body-light">
    <div class="content content-full">
        <div class="d-flex flex-column flex-sm-row justify-content-sm-between align-items-sm-center">
            <div class="flex-grow-1">
                <h1 class="h3 fw-bold mb-1">
                    Add Product
                </h1>
            </div>
            <nav class="flex-shrink-0 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-alt">
                    <li class="breadcrumb-item">
                        <a class="link-fx" href="{{ route('admin.products.index') }}">Products</a>
                    </li>
                    <li class="breadcrumb-item" aria-current="page">
                        Create
                    </li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="content">
    <!-- Info -->
    <div class="row">
        <div class="col-md-7">
            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Info</h3>
                </div>
                <div class="block-content">
                    <form action="{{ route('admin.products.store') }}" method="POST" id="product-form">
                        @csrf
                        <x-input name="name" required />
                        <x-input name="slug" info="Leave it empty to Autogenerate & Must be Unique">
                            <x-slot name="feedback">eg: <a href="javascript:void(0)">{{ route('product.view', ':your-slug-here') }}</a></x-slot>
                        </x-input>

                        <div class="row">

                            <div class="col-md-6">
                                <x-input name="price" label="Price in BTC ({{AppSettings::get('currency_sign', '₿')}})" placeholder="Price..." type="number" step="any" min="0" required />
                            </div>

                            <div class="col-md-6">
                                <x-input name="stock" label="Initial Stock" placeholder="Stock eg: 200" type="number" step="1" min="0" required />
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label" for="product-category">Category <span class="text-danger">*</span></label>
                                <select class="js-select2 form-select @error('category_id') is-invalid @enderror" id="product-category" name="category_id" style="width: 100%;" data-placeholder="Choose one..">
                                    <option></option>
                                    @foreach(\App\Services\CategoryService::getAll() as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <x-input name="discount" placeholder="eg: 20" type="number" step="any" min="0" info="Discount will be directly deducted from the price." />
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label" for="tags">Tags</label>
                            <select class="js-select2 form-select @error('tags') is-invalid @enderror" id="tags" name="tags[]" style="width: 100%;" data-placeholder="Choose many.." multiple data-tags="true">
                                <option></option>
                                @foreach (old('tags', []) as $tag)
                                <option value="{{ $tag }}">{{ ucwords($tag) }}</option>
                                @endforeach
                            </select>
                            @error('tags')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="content" class="form-label">Description</label>
                            <textarea id="content" name="description">{!! old('description') !!}</textarea>
                            @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label class="form-label">Status</label>
                                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                                    @foreach (App\Enums\ProductStatusEnum::options() as $key => $value)
                                    <option value="{{ $value }}" <?= old('status') == $value || $loop->index === 0 ? 'selected' : '' ?>>{{ $key }}</option>
                                    @endforeach
                                </select>
                                @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <x-input name="delivery_fee" placeholder="eg: 20" type="number" step="any" min="0" info="Delivery Fee for each product" />
                            </div>
                        </div>

                        <div class="mb-4">
                            <a class="btn btn-alt-danger me-2" href="{{ route('admin.products.index') }}">
                                <i class="fa fa-arrow-left mr-1"></i>
                                Back to Products
                            </a>
                            <button type="submit" class="btn btn-alt-primary">
                                <i class="fa fa-save me-1"></i>
                                New Product
                            </button>
                        </div>

                </div>
            </div>
        </div>

        <div class="col-md-5">
            <!-- Meta Data -->

            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Meta Data</h3>
                </div>
                <div class="block-content">
                    @settings('upc_enabled')
                    <div class="mb-4">
                        <x-input name="upc" placeholder="012212133211" feedback="Leave it empty to auto generate" label="Universal Product Code (UPC)" info="The Universal Product Code (UPC or UPC code) is a barcode symbology that is widely used worldwide for tracking trade items in stores." />
                    </div>
                    @endsettings
                    <div class="mb-4">
                        <label class="form-label" for="product-meta-title">Title</label>
                        <input type="text" class="js-maxlength form-control @error('title') is-invalid @enderror" id="product-meta-title" name="title" value="{{ old('title') }}" maxlength="55" data-always-show="true" data-placement="top">
                        <div class="form-text">
                            55 Character Max
                        </div>
                        @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label" for="product-meta-description">Short Description</label>
                        <textarea class="js-maxlength form-control @error('short_description') is-invalid @enderror" id="product-meta-description" name="short_description" rows="3" maxlength="115" data-always-show="true" data-placement="top">{{ old('short-description') }}</textarea>
                        <div class="form-text">
                            115 Character Max
                        </div>
                        @error('short_description')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <!-- <div class="mb-4">
                        <button type="submit" class="btn btn-alt-primary">Save</button>
                    </div> -->

                </div>
            </div>

            <x-key-value-input name="options" />
            </form>
            <!-- Media -->

            <div class="block block-rounded">
                <div class="block-header block-header-default">
                    <h3 class="block-title">Media</h3>
                </div>
                <div class="block-content block-content-full">
                    <form action="{{ route('image.upload') }}" id="image-upload" class="dropzone"></form>
                </div>
            </div>


        </div>
    </div>
</div>

@endsection

@section('js')
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script defer src="{{ asset('js/lib/jquery.min.js') }}"></script>
<script defer src="{{ asset('js/plugins/select2/js/select2.full.min.js') }}"></script>
<script defer src="{{ asset('js/plugins/bootstrap-maxlength/bootstrap-maxlength.min.js') }}"></script>
<script defer src="{{ asset('js/plugins/dropzone/min/dropzone.min.js') }}"></script>
@include('backend.partials.ckeditor')
<script>
    document.addEventListener("DOMContentLoaded",
        function() {
            $('button[data-action="sidebar_mini_toggle"]').trigger('click');
            initEditor(document.querySelector('#content'));
            One.helpers(['jq-select2', 'jq-maxlength']);


            let uploadedMap = {};
            let myDropzone = Dropzone.options.imageUpload = {
                url: "{{ route('image.upload') }}",
                maxFilesize: 2, // MB
                acceptedFiles: ".jpeg,.jpg,.png,.gif,.webp,.svg,.avif",
                addRemoveLinks: true,
                headers: {
                    'X-CSRF-TOKEN': "{{ csrf_token() }}"
                },
                success: function(file, response) {
                    $('#product-form').append('<input type="hidden" name="images[]" value="' + response.name + '">');
                    uploadedMap[file.name] = response.name
                },
                removedfile: function(file) {
                    file.previewElement.remove()
                    let name = ''
                    if (typeof file.file_name !== 'undefined') {
                        name = file.file_name
                    } else {
                        name = uploadedMap[file.name]
                    }
                    $('#product-form').find('input[name="images[]"][value="' + name + '"]').remove()
                },
            }
        });
</script>

@endsection