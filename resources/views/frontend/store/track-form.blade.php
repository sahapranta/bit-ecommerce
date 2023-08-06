@extends('layouts.simple')

@section('content')
<x-hero />
<section class="container py-5">
    <div class="block block-rounded">
        <div class="block-header block-header-default">
            <h3 class="block-title">Track Your Order</h3>
        </div>
        <div class="block-content">
            <form action="{{ route('order.track') }}" method="get">
                <div class="mb-4">
                    <label for="order" class="form-label">ORDER NUMBER</label>
                    <input type="text" class="form-control" id="order" name="order" placeholder="Enter your order number" value="{{ old('order') }}">
                </div>
                <div class="mb-4">
                    <button type="submit" class="btn btn-alt-info">Submit</button>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection