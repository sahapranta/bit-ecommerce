@extends('layouts.simple')

@section('content')
<x-hero>
    <h1 class="h1 text-white">{{ $page->title }}</h1>
    <p class="h4 text-white-75 mt-3 mb-0">{{ $page->subtitle }}</p>
</x-hero>
<div class="content">
    <div class="row">
        <div class="col-md-10 offset-md-1">
            <div class="block">
                <div class="block-content">
                    {!! $page->body !!}
                </div>
            </div>
        </div>
        <!-- <div class="col-md-3">
            <div class="block">
                <div class="block-header">
                    <h3 class="block-title">Categories</h3>
                </div>
                <div class="block-content">
                </div>
            </div>
        </div> -->
    </div>
</div>
@endsection