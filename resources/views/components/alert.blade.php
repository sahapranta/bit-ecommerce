@props(['type' => 'success', 'message'])

@php
    $attributes = $attributes->class([
        'alert alert-dismissible fade show',
        'alert-success' => $type === 'success',
        'alert-danger' => $type === 'error' || $type === 'danger',
        'alert-warning' => $type === 'warning',
        'alert-info' => $type === 'info',
    ]);

    $attributes = $attributes->merge([
        'role' => 'alert',
    ]);
@endphp

<div {{ $attributes }}>
    <strong class="text-uppercase">{{$type}}!</strong> {{ $message }}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>