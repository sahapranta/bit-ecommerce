@extends('layouts.simple')

@section('content')
<x-hero image="media/photos/photo10@2x.jpg" />

<!-- Page Content -->
<div class="content content-boxed">
    <div class="row">
        <div class="col-md-4">
            @include('frontend.user._sidebar')
        </div>
        <div class="col-md-8">
            @yield('subcontent')
        </div>
    </div>
</div>

@endsection
