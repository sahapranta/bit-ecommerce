<div id="product_reviews">
    <!-- Average Rating -->
    <div class="block block-rounded bg-body-light">
        <div class="block-content text-center">
            <x-star-rating :rating="$product->ratings" iconClass="fa-2x" />
            <p class="pt-2">
                <strong>{{ $product->ratings }}</strong>/5.0 out of <strong>{{ $reviews->count() }}</strong> Ratings
            </p>
        </div>
    </div>

    <!-- Ratings -->
    @foreach ($reviews as $review)
    <div class="d-flex push">
        <a class="flex-shrink-0 me-3" href="javascript:void(0)">
            <x-image class="img-avatar img-avatar32" src="{{ $review->user->avatar }}" alt="User Avatar" avatar/>
        </a>
        <div class="flex-grow-1">
            <x-star-rating :rating="$review->rating" />
            <a class="fw-semibold" href="javascript:void(0)">{{ $review->is_anonymous ? 'Anonymous' : $review->user->name }}</a>
            <p class="my-1">{{ $review->review }}</p>
            <span class="text-muted"><em>{{ $review->created_at->diffForHumans() }}</em></span>
        </div>
    </div>
    @endforeach

    <div class="w-100" wire:loading>
        <?php for ($i = 0; $i < rand(1, 3); $i++) : ?>
            <div class="d-flex push">
                <a class="flex-shrink-0 me-3" href="javascript:void(0)">
                    <img src="{{ asset('media/avatars/avatar.jpg') }}" class="img-avatar img-avatar32" alt="Comment Avatar">
                </a>
                <div class="flex-grow-1">
                    <div class="placeholder-glow">
                        <span class="placeholder col-3"></span>
                        <div class="col-9"></div>
                        <span class="placeholder col-2"></span>
                        <span class="placeholder col-3"></span>
                        <span class="placeholder col-3"></span>
                        <span class="placeholder col-4"></span>
                        <span class="placeholder col-6"></span>
                    </div>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('livewire:load', function() {
        const element = document.querySelector('#product_reviews');
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    // When the component becomes visible in the viewport, trigger a Livewire action to load the data.
                    @this.loadReviews();
                    observer.unobserve(entry.target);
                }
            });
        }, {
            root: null,
            rootMargin: '0px',
            threshold: 0.1,
        });
        observer.observe(element);
    });
</script>
@endpush