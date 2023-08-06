@extends('layouts.simple')

@section('css')
<link rel="stylesheet" href="{{ asset('js/plugins/magnific-popup/magnific-popup.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick-theme.css') }}">
<link rel="stylesheet" href="{{ asset('js/plugins/slick-carousel/slick.css') }}">
<style>
    /* the slides */
    .slick-slide {
        margin: 0 10px;
    }

    /* the parent */
    .slick-list {
        margin: 0 -10px;
    }
</style>
@endsection

@section('content')
<x-hero image="{{ $product->image }}">
    <h1 class="h2 text-white mb-2">{{ $product->name }}</h1>
    @if ($product->title)
    <h2 class="h4 fw-normal text-white-75 mb-0">{{ $product->title }}</h2>
    @endif
</x-hero>

<!-- END Hero -->

<!-- Page Content -->
<div class="content">
    <!-- Toggle Side Content -->
    <!-- Class Toggle, functionality initialized in Helpers.oneToggleClass() -->
    <!-- <div class="d-xl-none push">
        <div class="row g-sm">
            <div class="col-6">
                <button type="button" class="btn btn-alt-secondary w-100" data-toggle="class-toggle" data-target=".js-ecom-div-nav" data-class="d-none">
                    <i class="fa fa-fw fa-bars text-muted me-1"></i> Category
                </button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-alt-secondary w-100" data-toggle="class-toggle" data-target=".js-ecom-div-cart" data-class="d-none">
                    <i class="fa fa-fw fa-shopping-cart text-muted me-1"></i> Cart (3)
                </button>
            </div>
        </div>
    </div> -->
    <!-- END Toggle Side Content -->

    <div class="row">
        <div class="col-xl-4 order-xl-1">
            <!-- Ads Block -->
            <div class="block block-rounded">
                <div class="p-2">
                    <a href="#">
                        <img class="img-fluid" src="{{ asset('media/ads.webp') }}" alt="Ads Banner">
                    </a>
                </div>
            </div>

            <!-- Categories -->
            <div class="block block-rounded js-ecom-div-nav d-none d-xl-block">
                <div class="block-header block-header-default">
                    <h3 class="block-title">
                        <i class="fa fa-fw fa-boxes text-muted me-1"></i> Categories
                    </h3>
                </div>
                <div class="block-content">
                    <ul class="nav nav-pills flex-column push">
                        @foreach ($categories as $category)
                        <li class="nav-item mb-1">
                            <a class="nav-link d-flex justify-content-between align-items-center @if($product->category_id == $category->id) active @endif" href="{{ route('home.products.category', $category->slug) }}">
                                {{$category->name}} <span class="badge rounded-pill bg-black-50 ms-1">{{ $category->products_sum_sales }}</span>
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
        <div class="col-xl-8 order-xl-0">
            <!-- Product -->
            <div class="block block-rounded">
                <div class="block-content">
                    <!-- Vitals -->
                    <div class="row items-push">
                        <div class="col-md-6">
                            <!-- Images -->
                            <div class="row g-sm js-gallery img-fluid-100">
                                <div class="col-12 mb-3">
                                    @php
                                    $images = $product->media;
                                    @endphp

                                    @forelse ($images as $media)
                                    @if($loop->first)
                                    <a class="img-link img-link-zoom-in img-lightbox" href="{{ $media->getFullUrl() }}">
                                        <x-image class="img-fluid main-image" src="{{ $media->getFullUrl('thumbnail') }}" alt="{{ $product->name }}" />
                                    </a>
                                    @else
                                    <a class="d-none img-link img-link-zoom-in img-lightbox" href="{{ $media->getFullUrl() }}"></a>
                                    @endif
                                    @empty
                                    <x-image class="img-fluid" src="{{ AppHelper::placeholder() }}" alt="{{ $product->name }}" />
                                    @endforelse

                                </div>
                            </div>
                            @if ($images && count($images) > 1)
                            <div class="row g-sm img-fluid-100 px-2">
                                <div class="col-12 js-slider">
                                    @foreach ($images as $media)
                                    <a href="{{ $media->getFullUrl() }}">
                                        <x-image class="img-fluid" src="{{ $media->getFullUrl('thumbnail') }}" alt="{{ $media->name }} Image" />
                                    </a>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                            <!-- END Images -->
                        </div>
                        <div class="col-md-6">
                            <!-- Info -->
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    @if ($product->stock === 0)
                                    <div class="fs-sm fw-semibold text-danger">OUT OF STOCK</div>
                                    @else
                                    <div class="fs-sm fw-semibold text-success">IN STOCK</div>
                                    @endif
                                    <div class="fs-sm text-muted">{{ $product->stock }} Available</div>
                                </div>
                                <div class="fs-2 fw-bold">
                                    {{ AppHelper::moneyWithSymbol($product->price) }}
                                </div>
                            </div>
                            <form class="d-flex justify-content-between my-3 border-top border-bottom" action="" method="post" onsubmit="return false;">
                                <div class="py-3">
                                    <button type="submit" class="btn btn-sm btn-alt-secondary" onclick="Livewire.emit('addToCart', '{{ $product->slug }}')">
                                        <i class="fas fa-plus text-success me-1"></i> Add to Cart
                                    </button>
                                    <button type="submit" class="btn btn-sm btn-alt-secondary ms-2" onclick="buyNow('{{ $product->slug }}')">
                                        <i class="fas fa-shopping-cart text-danger me-1"></i> Buy Now
                                    </button>
                                </div>
                            </form>
                            <a href="{{ route('home.products.category', $product->category->slug) }}" class="text-muted text-uppercase">{{ $product->category->name }}</a>
                            <p class="mt-2">{{ $product->short_description  }}</p>
                            <!-- END Info -->
                        </div>
                    </div>
                    <!-- END Vitals -->



                    <!-- Extra Info Tabs -->
                    <div class="block block-rounded">
                        <ul class="nav nav-tabs nav-tabs-alt align-items-center" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" id="ecom-product-info-tab" data-bs-toggle="tab" data-bs-target="#ecom-product-info" role="tab" aria-controls="ecom-product-reviews" aria-selected="true">Info</button>
                            </li>
                            @settings('comment_system_enabled')
                            <li class="nav-item">
                                <button type="button" class="nav-link" id="ecom-product-comments-tab" data-bs-toggle="tab" data-bs-target="#ecom-product-comments" role="tab" aria-controls="ecom-product-reviews" aria-selected="false">Comments</button>
                            </li>
                            @endsettings
                            @settings('review_system_enabled')
                            <li class="nav-item">
                                <button type="button" class="nav-link" id="ecom-product-reviews-tab" data-bs-toggle="tab" data-bs-target="#ecom-product-reviews" role="tab" aria-controls="ecom-product-reviews" aria-selected="false">Reviews</button>
                            </li>
                            @endsettings
                        </ul>
                        <div class="block-content tab-content">
                            <!-- Info -->
                            <div class="tab-pane pull-x active" id="ecom-product-info" role="tabpanel" aria-labelledby="ecom-product-info-tab" tabindex="0">
                                {!! $product->description !!}

                                @if ($product->tags)
                                <div class="fs-sm fw-semibold text-muted mt-4 mb-2">Tags:</div>
                                <div class="fs-sm mb-3">
                                    @foreach ($product->tags as $tag)
                                    <a class="badge bg-secondary me-1 mb-1 text-uppercase" href="{{ route('home.products.tags', $tag) }}">{{ $tag }}</a>
                                    @endforeach
                                </div>
                                @endif

                                @if (!empty($product->options))
                                <table class="table table-striped table-borderless">
                                    <thead>
                                        <tr>
                                            <th colspan="2">Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($product->options as $name => $values)
                                        <tr>
                                            <td style="width: 20%;">{{ $name }}</td>
                                            <td>
                                                {{ is_array($values) ? implode(', ', $values) : $values }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @endif
                            </div>
                            <!-- END Info -->
                            @settings('comment_system_enabled')
                            <!-- Comments -->
                            <div class="tab-pane pull-x fs-sm" id="ecom-product-comments" role="tabpanel" aria-labelledby="ecom-product-comments-tab" tabindex="0">
                                <livewire:product-comment :product="$product" />
                            </div>
                            @endsettings
                            @settings('review_system_enabled')
                            <!-- Reviews -->
                            <div class="tab-pane pull-x fs-sm" id="ecom-product-reviews" role="tabpanel" aria-labelledby="ecom-product-reviews-tab" tabindex="0">
                                <livewire:product-review :product="$product" />
                            </div>
                            @endsettings
                        </div>
                    </div>
                    <!-- END Extra Info Tabs -->
                </div>
            </div>
            <!-- END Product -->
        </div>
    </div>
</div>
<!-- END Page Content -->

@endsection

@section('js')
<script src="{{ asset('js/lib/jquery.min.js') }}"></script>
<script src="{{ asset('js/plugins/magnific-popup/jquery.magnific-popup.min.js') }}"></script>
<script src="{{ asset('js/plugins/slick-carousel/slick.min.js') }}"></script>
<script>
    function buyNow(id) {
        Livewire.emit('addToCart', id);
        setTimeout(() => {
            window.location.href = "{{ route('checkout') }}";
        }, 1000);
    }

    $(document).ready(function() {
        $('.js-gallery').each(function() {
            $(this).magnificPopup({
                delegate: 'a',
                type: 'image',
                gallery: {
                    enabled: true
                }
            });
        });

        $('.js-slider:not(.js-slider-enabled)').each((index, element) => {
            let el = $(element);
            let gallery = $('.js-gallery');

            el.find('img').on('click', function(e) {
                e.preventDefault();
                let prevSrc = gallery.find('.main-image').attr('src');
                let prevHref = gallery.find('a:first').attr('href');

                let src = $(this).attr('src');
                let href = $(this).parent().attr('href');

                let found = gallery.find('a').filter(function() {
                    return $(this).attr('href') == href;
                });

                found.attr('href', prevHref).find('img').attr('src', prevSrc);

                gallery.find('.main-image').attr('src', src).parent().attr('href', href);


            });

            // Add .js-slider-enabled class to tag it as activated and init it
            el.addClass('js-slider-enabled').slick({
                arrows: true,
                dots: true,
                slidesToShow: 2,
                centerMode: true,
                autoplay: false,
                autoplaySpeed: 3000,
                // infinite: typeof el.data('infinite') === 'undefined' ? true : el.data('infinite')
            });
        });
    });
</script>
@endsection