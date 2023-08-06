@extends('layouts.simple')

@section('content')

<x-hero>
  <h3 class="h2 mb-3 text-white">{{ AppSettings::get('site_title', 'Welcome to our Digital Store!') }}</h3>
  <form action="{{ route('search') }}" method="get">
    <div class="input-group w-50 mx-auto">
      <input type="search" class="form-control home-search" placeholder="Search products.." name="q">
      <button type="submit" class="btn btn-outline-light">
        <i class="fa fa-fw fa-search"></i>
      </button>
    </div>
  </form>
</x-hero>

<!-- Page Content -->
<div class="content content-full content-boxed">

  <div class="row items-push">
    @forelse ($products as $product)
    <div class="col-sm-6 col-md-4 col-xl-3">
      <x-product-card :product="$product" />
    </div>
    @empty
    <div class="col-md-12">
      <x-no-product />
    </div>
    @endforelse

    <div class="d-flex justify-content-center pt-2">
      {{ $products->links() }}
    </div>
  </div>

</div>
<!-- END Page Content -->

<!-- Explore Store -->
<div class="bg-body-dark">
  <div class="content content-full">
    <div class="my-5 text-center">
      <h3 class="h4 mb-4">
        Find your <strong>needed</strong> products!
      </h3>
      <a class="btn btn-primary px-4 py-2" href="{{ route('search') }}">
        Explore Store <i class="fa fa-arrow-right ms-1"></i>
      </a>
    </div>
  </div>
</div>
<!-- END Explore Store -->

@endsection