@props(['placeholder'=>AppHelper::placeholder(), 'avatar'=>false])
@php
$img = $avatar ? asset('media/avatars/avatar.jpg') : $placeholder;
@endphp
<img {{ $attributes->merge([
        'class' => 'img-fluid',
        'onerror'=>"this.onerror=null;this.src='{$img}'",
    ]) }} />