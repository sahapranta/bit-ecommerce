@extends('layouts.backend')

@section('css')
<link rel="stylesheet" href="{{ asset('js/plugins/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/dropzone/min/dropzone.min.css') }}">
<style>
  .dropzone .dz-preview.dz-image-preview {
    background: transparent !important;
  }

  .dz-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }

  .ck-editor__editable[role="textbox"] {
    /* editing area */
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
          Edit Product
        </h1>
      </div>
      <nav class="flex-shrink-0 mt-sm-0 ms-sm-3" aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-alt">
          <li class="breadcrumb-item">
            <a class="link-fx" href="{{ route('admin.products.index') }}">Products</a>
          </li>
          <li class="breadcrumb-item" aria-current="page">
            Edit
          </li>
        </ol>
      </nav>
    </div>
  </div>
</div>
<div class="content">
  <!-- Quick Overview + Actions -->
  <div class="row">
    <div class="col-6 col-lg-4">
      <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
        <div class="block-content block-content-full">
          <div class="fs-2 fw-semibold text-success">{{ $product->sales }}</div>
        </div>
        <div class="block-content py-2 bg-body-light">
          <p class="fw-medium fs-sm text-success mb-0">
            Sales
          </p>
        </div>
      </a>
    </div>
    <div class="col-6 col-lg-4">
      <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)">
        <div class="block-content block-content-full">
          <div class="fs-2 fw-semibold text-dark">{{ $product->stock }}</div>
        </div>
        <div class="block-content py-2 bg-body-light">
          <p class="fw-medium fs-sm text-muted mb-0">
            Available
          </p>
        </div>
      </a>
    </div>
    <div class="col-lg-4">
      <a class="block block-rounded block-link-shadow text-center" href="javascript:void(0)" onclick="deleteProduct()">
        <div class="block-content block-content-full">
          <div class="fs-2 fw-semibold text-danger">
            <i class="fa fa-times"></i>
          </div>
        </div>
        <div class="block-content py-2 bg-body-light">
          <p class="fw-medium fs-sm text-danger mb-0">
            Remove Product
          </p>
        </div>
      </a>
    </div>
  </div>
  <!-- END Quick Overview + Actions -->

  <!-- Info -->
  <form action="{{ route('admin.products.update', $product->id) }}" method="POST" id="product-form">
    @method('PUT')
    <div class="row">
      <div class="col-md-7">
        <div class="block block-rounded">
          <div class="block-header block-header-default">
            <h3 class="block-title">Info</h3>
          </div>
          <div class="block-content">

            @csrf
            <x-input name="name" required :value="$product->name" />
            <x-input name="slug" :value="$product->slug" info="Leave it empty to Autogenerate & Must be Unique">
              <x-slot name="feedback">eg: <a href="{{ route('product.view', $product->slug) }}" target="_blank" tabindex="0">{{ route('product.view', $product->slug) }}</a></x-slot>
            </x-input>

            <div class="row">

              <div class="col-md-6">
                <x-input name="price" :value="$product->price" label="Price in {{ AppSettings::get('currency_code', 'GBP') }} ({{ AppSettings::get('currency_symbol', '£')}})" placeholder="Price..." type="number" step="any" min="0" required />
              </div>

              <div class="col-md-6">
                <x-input name="stock" :value="$product->stock" label="Initial Stock" placeholder="Stock eg: 200" type="number" step="1" min="0" required />
              </div>

              <div class="col-md-6 mb-4">
                <label class="form-label" for="product-category">Category <span class="text-danger">*</span></label>
                <select class="js-select2 form-select" id="product-category" name="category_id" style="width: 100%;" data-placeholder="Choose one..">
                  <option></option><!-- Required for data-placeholder attribute to work with Select2 plugin -->
                  @foreach(\App\Services\CategoryService::getAll() as $category)
                  <option value="{{ $category->id }}" <?= old('category_id', $product->category_id) == $category->id ? 'selected' : '' ?>>{{ $category->name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-md-6">
                <x-input name="discount" :value="$product->discount" placeholder="eg: 20" type="number" step="any" min="0" info="Discount will be directly deducted from the price." />
              </div>
            </div>

            <div class="mb-4">
              <label class="form-label" for="tags">Tags</label>
              <select class="js-select2 form-select @error('tags') is-invalid @enderror" id="tags" name="tags[]" style="width: 100%;" data-placeholder="Choose many.." multiple data-tags="true">
                <option></option>
                @foreach (old('tags', $product->tags??[]) as $tag)
                <option value="{{ $tag }}" selected>{{ $tag }}</option>
                @endforeach
              </select>
              @error('tags')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label for="content" class="form-label @error('description') is-invalid @enderror">Description</label>
              <textarea id="content" name="description">{!! old('description', $product->description) !!}</textarea>
              @error('description')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="row mb-4">
              <div class="col-md-4">
                <label class="form-label">Status</label>
                <select name="status" id="status" class="form-select @error('status') is-invalid @enderror">
                  @foreach (App\Enums\ProductStatusEnum::options() as $key => $value)
                  <option value="{{ $value }}" <?= old('status', $product->status->value) === $value ? 'selected' : '' ?>>{{ $key }}</option>
                  @endforeach
                </select>
                @error('status')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              {{--
                <div class="col-md-6">
                  <x-input name="Delivery Fee" :value="$product->delivery_fee" placeholder="eg: 20" type="number" step="any" min="0" info="Delivery Fee for each product" />
                </div>
                --}}
            </div>

            <div class="mb-4">
              <a class="btn btn-alt-danger me-2" href="{{ route('admin.products.index') }}">
                <i class="fa fa-arrow-left me-1"></i>
                Back to Products
              </a>
              <button type="submit" class="btn btn-alt-primary">
                <i class="fa fa-save me-1"></i>
                Update Product
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
              <x-input name="upc" :value="$product->upc" placeholder="012212133211" feedback="Leave it empty to auto generate" label="Universal Product Code (UPC)" info="The Universal Product Code (UPC or UPC code) is a barcode symbology that is widely used worldwide for tracking trade items in stores." />
            </div>
            @endsettings
            <div class="mb-4">
              <label class="form-label" for="product-meta-title">Title</label>
              <input type="text" class="js-maxlength form-control @error('title') is-invalid @enderror" id="product-meta-title" name="title" value="{{ old('title', $product->title) }}" maxlength="55" data-always-show="true" data-placement="top">
              <div class="form-text">
                55 Character Max
              </div>
              @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-4">
              <label class="form-label" for="product-meta-description">Short Description</label>
              <textarea class="js-maxlength form-control @error('short_description') is-invalid @enderror" id="product-meta-description" name="short_description" rows="3" maxlength="115" data-always-show="true" data-placement="top">{{ old('short_description', $product->short_description) }}</textarea>
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
        <x-key-value-input name="options" :data="@json_encode($product->options)" />
  </form>

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
  async function deleteProduct() {
    if (!confirm('Are you sure?')) return;
    const url = "{{ route('admin.products.destroy', $product->id) }}";
    try {
      const {
        data
      } = await axios.delete(url);
      toastr.success(data?.message || 'Product deleted successfully!');
      setTimeout(() => window.location = "{{ route('admin.products.index') }}", 500);
    } catch (error) {
      toastr.error(error?.response?.data?.message || 'Something went wrong!');
    }
  }

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
          uploadedMap[file.name] = response.name;
        },
        removedfile: function(file) {
          file.previewElement.remove();
          if (file && file.hasOwnProperty('id')) {
            $('#product-form').append('<input type="hidden" name="deleted_images[]" value="' + file.id + '">');
          } else {
            let name = '';
            if (typeof file.file_name !== 'undefined') {
              name = file.file_name;
            } else {
              name = uploadedMap[file.name];
            }
            $('#product-form').find('input[name="images[]"][value="' + name + '"]').remove();
          }
        },
        init: function() {
          <?php if ($images = isset($product) ? $product?->images : null) : ?>
            let files = <?= json_encode($images) ?>;
            for (let file of files) {
              this.emit('addedfile', file);
              this.emit('thumbnail', file, file.dataURL);
              this.emit('complete', file);

              // $('form').append('<input type="hidden" name="images[]" value="' + file.name + '">');
            }
          <?php endif; ?>
        }
      }
    });
</script>

@endsection