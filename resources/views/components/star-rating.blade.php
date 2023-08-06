@props(['rating'=>0,'reviews'=>0, 'iconClass'=>''])

@php
    $fullStars = floor($rating);
    $halfStar = ceil($rating) - $fullStars;
    $emptyStars = 5 - $fullStars - $halfStar;
@endphp

<div {{ $attributes->merge(['class' => 'text-warning']) }}>
    @for ($i = 0; $i < $fullStars; $i++)
        <i class="fa fa-star {{ $iconClass }}"></i>
    @endfor

    @if ($halfStar > 0)
        <i class="fa fa-star-half-alt {{ $iconClass }}"></i>
    @endif

    @for ($i = 0; $i < $emptyStars; $i++)
        <i class="fa fa-star text-muted {{ $iconClass }}"></i>
    @endfor

    @if($slot != null || $slot != '')
        {!! $slot !!}
    @else
        <span class="text-white">({{ $reviews }})</span>
    @endif
</div>
