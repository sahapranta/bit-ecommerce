@props(['mark' => null])

@if ($mark)
@php
$mark = strtolower($mark);

$attributes = $attributes->class([
    'fw-semibold text-capitalize',
    'text-success'=> $mark == 'admin',
    'text-danger' => $mark == 'purchased',
]);

@endphp

<mark {{ $attributes }}>
    {{ $mark }}
</mark>
@endif