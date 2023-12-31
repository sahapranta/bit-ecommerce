@props(['image' => 'media/photos/photo3@2x.jpg', 'imageClass' => 'bg-image', 'containerClass'=> 'bg-primary-dark-op' ])
<!-- Hero -->
<div class="{{ $imageClass }}" style="<?= $image ? "background-image: url('" . asset($image) . "')" : ''; ?>">
    <div class="{{ $containerClass }}">
        <div {{
            $attributes->merge([
                'class' => 'content content-full text-center py-6'
            ])
        }}>
            @if (isset($slot) && $slot->isNotEmpty())
            {!! $slot !!}
            @else
            <h1 class="h2 text-white mb-2">Welcome to our Digital Store!</h1>
            <h2 class="h4 fw-normal text-white-75 mb-0">Feel free to explore over 50.000 products.</h2>
            @endif
        </div>
    </div>
</div>
<!-- END Hero -->