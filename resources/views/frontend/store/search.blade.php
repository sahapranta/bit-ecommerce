@extends('layouts.simple')

@section('css')
<link rel="stylesheet" href="{{ asset('js/plugins/ion-rangeslider/css/ion.rangeSlider.min.css') }}">
<style>
    ul.pagination {
        justify-content: space-between;
        display: flex;
    }

    ul.pagination .page-item.disabled .page-link {
        background-color: transparent;
        border: none;
    }
</style>
@endsection

@section('content')
<x-hero />

<div class="content">
    <!-- Toggle Side Content -->
    <!-- Class Toggle, functionality initialized in Helpers.oneToggleClass() -->
    <div class="d-xl-none push">
        <div class="row g-sm">
            <div class="col-12">
                <button type="button" class="btn btn-alt-secondary w-100" data-toggle="class-toggle" data-target=".js-ecom-div-filters" data-class="d-none">
                    <i class="fa fa-fw fa-filter text-muted me-1"></i> Filters
                </button>
            </div>
            <!-- <div class="col-6">
                    <button type="button" class="btn btn-alt-secondary w-100" data-toggle="class-toggle" data-target=".js-ecom-div-cart" data-class="d-none">
                        <i class="fa fa-fw fa-shopping-cart text-muted me-1"></i> Cart (3)
                    </button>
                </div> -->
        </div>
    </div>
    <!-- END Toggle Side Content -->

    <div class="row push">
        <div class="col-xl-4 pe-md-4 order-xl-0">
            <form action="{{ route('search') }}" method="get" id="filterForm">
                <div class="pb-3 btn-group w-100">
                    <a class="btn btn-alt-warning" href="{{ route('search') }}">
                        <i class="fas fa-refresh me-1"></i>
                        Reset
                    </a>
                    <button type="submit" class="btn btn-alt-success">
                        <i class="fas fa-filter me-1"></i>
                        Filter
                    </button>
                </div>
                <!-- Misc Filters -->
                <div class="block block-rounded js-ecom-div-filters d-none d-xl-block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <i class="fa fa-fw fa-tools text-muted me-1"></i> Misc
                        </h3>
                    </div>
                    <div class="block-content">
                        <div class="mb-4">
                            <label for="ratings" class="form-label">
                                <i class="fa fa-fw fa-star text-muted me-1"></i>
                                Ratings
                            </label>
                            <select id="ratings" name="ratings" class="form-select" size="1">
                                <option value="" selected>ALL</option>
                                @foreach ([5,4,3,2,1] as $val)
                                <option value="{{ $val }}" @if ($filter['ratings']==$val) selected @endif>{{ $val }} Stars</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label"><i class="fa fa-fw fa-coins text-muted me-1"></i> Price Range</label>
                            <input type="text" class="js-rangeslider" id="price_range" name="price_range" data-type="double" data-grid="true" data-min="{{ $price['min'] }}" data-max="{{ $price['max'] }}" data-from="{{ data_get($filter, 'price.min') }}" data-to="{{ data_get($filter, 'price.max') ?: $price['max'] }}" data-prefix="{{ AppHelper::getCurrencySymbol() }}">
                        </div>
                    </div>
                </div>

                <!-- Categories Filters -->
                <div class="block block-rounded js-ecom-div-filters d-none d-xl-block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <i class="fa fa-fw fa-boxes text-muted me-1"></i> Categories
                        </h3>
                    </div>
                    <div class="block-content">
                        <div class="mb-4">
                            @foreach ($categories as $category)
                            <div class="form-check mb-1">
                                <input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="category-{{ $category->id }}" name="category[]" @if(in_array($category->id, $filter['category'])) checked @endif>
                                <label class="form-check-label cursor-pointer" for="category-{{ $category->id }}">{{ $category->name }} ({{ $category->products_count }})</label>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="block block-rounded js-ecom-div-filters d-none d-xl-block">
                    <div class="block-header block-header-default">
                        <h3 class="block-title">
                            <i class="fa fa-fw fa-tags text-muted me-1"></i> Tags
                        </h3>
                    </div>
                    <div class="block-content">
                        <div class="mb-4">
                            @foreach ($tags as $tag)
                            <a href="{{ route('search', ['tag'=> $tag]) }}" class="text-uppercase badge {{ request()->get('tag') == $tag ? 'bg-primary-light':'bg-secondary' }} rounded me-1 mb-1">{{ $tag }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-xl-8 order-xl-1">
            @if (false)
            <div class="bg-body-dark fw-semibold text-muted rounded p-3 push text-center">
                {{ $products->count() }} products were found
                <!-- for <mark class="fw-semibold text-danger rounded px-1">inspiring</mark> -->
            </div>
            @endif

            <div class="bg-body-dark">
                <form action="" method="get">
                    <div class="input-group mb-4">
                        <input type="search" class="form-control" placeholder="Search products.." value="{{ $filter['search'] }}" name="q">
                        <button type="submit" class="btn btn-alt-danger">
                            <i class="fa fa-fw fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
            <!-- Sort and Show Filters -->
            <div class="d-flex justify-content-between">
                <div class="mb-3">
                    <select id="perPage" name="per_page" class="form-select form-select-sm" size="1">
                        <option value="0" disabled selected>SHOW</option>
                        <option value="9">9</option>
                        @foreach ([18, 36, 72] as $val)
                        <option value="{{ $val }}" @if($val==$filter['per_page']) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <select id="sort" name="sort" class="form-select form-select-sm" size="1">
                        <option value="0" disabled selected>SORT BY</option>
                        @foreach ([
                        'reviews' => 'Popularity',
                        'name_asc' => 'Name (A to Z)',
                        'name_desc' => 'Name (Z to A)',
                        'price_asc' => 'Price (Lowest to Highest)',
                        'price_desc' => 'Price (Highest to Lowest)',
                        'sales_asc' => 'Sales (Lowest to Highest)',
                        'sales_desc' => 'Sales (Highest to Lowest)',
                        ] as $key=>$val)
                        <option value="{{ $key }}" @if($key==$filter['sort']) selected @endif>{{ $val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- END Sort and Show Filters -->

            <!-- Product Results -->
            <div class="row items-push">
                @forelse ($products as $product)
                <div class="col-md-6 col-xl-4">
                    <x-product-card :product="$product" />
                </div>
                @empty
                <div class="col-md-12">
                    <x-no-product />
                </div>
                @endforelse
            </div>
            <div>
                {{ $products->links()  }}
            </div>
            <!-- END Product Results -->
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="{{ asset('js/lib/jquery.min.js') }}"></script>
<script src="{{ asset('js/plugins/ion-rangeslider/js/ion.rangeSlider.min.js') }}"></script>
<script>
    $(document).ready(function() {
        $('.js-rangeslider').ionRangeSlider({
            input_values_separator: ',',
            skin: 'round'
        });

        $('#sort').change(function() {
            let sort = $(this).val();
            $('#filterForm').append('<input type="hidden" name="sort" value="' + sort + '">');
            $('#filterForm').submit();
        });

        $('#perPage').change(function() {
            let perPage = $(this).val();
            $('#filterForm').append('<input type="hidden" name="per_page" value="' + perPage + '">');
            $('#filterForm').submit();
        });
    });
</script>
@endsection