@extends('layouts.simple')

@section('css')
<style>
    .progresses {
        display: flex;
        align-items: center;
    }

    .line {
        width: 33.33%;
        height: 6px;
        background: #c5cae9;
    }

    .line.active,
    .steps.active {
        background: #6520ff;
    }

    .steps {
        display: flex;
        background: #c5cae9;
        color: #fff;
        width: 40px;
        height: 36px;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
    }

    @media (max-width: 767px) {
        .info div i {
            font-size: 2rem !important;
            text-align: center !important;
        }

        .info div p {
            font-size: 0.75rem !important;
        }
    }
</style>
@endsection

@section('content')
<x-hero />
<section class="container py-5">
    <div class="row d-flex justify-content-center align-items-center">
        <div class="col-12">
            <div class="card card-stepper py-4" style="border-radius: 16px;">

                <div class="card-body p-5">

                    <div class="d-md-flex justify-content-between align-items-center mb-5">
                        <div>
                            <h5 class="mb-2 mb-md-0 font-weight-bold">#{{ $order->order_id }}</h5>
                        </div>
                        <div class="text-md-end text-muted">
                            <p class="mb-0">Expected Arrival: <span>{{ $order->delivery_date?->format('F - d - Y') ?? 'TBA'}}</span></p>
                            <p class="mb-0">USPS: <span class="font-weight-bold">{{ $order->tracking_id }}</span></p>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mx-0 mt-0 mb-5 px-0 pt-0 pb-2">
                        @foreach ($status as $item)
                        <div class="steps @if($item['status']) active @endif">
                            <span><i class="fas fa-circle @if($item['status']) fa-check @endif"></i></span>
                        </div>

                        @unless ($loop->last)
                        <span class="line @if($status[$loop->index+1]['status']) active @endif"></span>
                        @endunless
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-between info">
                        @foreach ($status as $item)
                        <div class="d-lg-flex align-items-center">
                            <i class="fas {{ $item['icon'] }} {{ $item['color'] }}  fa-3x me-lg-4 mb-3 mb-lg-0"></i>
                            <div>
                                <p class="fw-bold $item['color'] mb-1">Order</p>
                                <p class="fw-bold $item['color'] mb-0">{{ $item['name'] }}</p>
                            </div>
                        </div>
                        @endforeach

                    </div>


                </div>
                <p class="text-center h5 py-3 text-secondary">{{ __('Thank you for shopping with us.') }}</p>

                <a href="{{ route('home') }}" class="btn btn-alt-warning mx-auto">
                    <i class="fas fa-home me-2"></i>
                    Back to Shop
                </a>
            </div>
        </div>
    </div>
</section>
@endsection