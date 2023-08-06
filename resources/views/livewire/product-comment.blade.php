<div id="product-comments">
    <x-comment-form />
    @forelse ($comments as $comment)
    <x-comment :comment="$comment" />
    @empty
    <div class="text-muted text-center">
        😀 No comments yet.
    </div>
    @endforelse
    <div class="w-100" wire:loading>
        <?php for ($i = 0; $i < rand(1, 3); $i++) : ?>
            <div class="d-flex push">
                <a class="flex-shrink-0 me-3" href="javascript:void(0)">
                    <img src="{{ asset('media/avatars/avatar3.jpg') }}" class="img-avatar img-avatar32" alt="Comment Avatar">
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
        const element = document.querySelector('#product-comments');
        const observer = new IntersectionObserver((entries, observer) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    // When the component becomes visible in the viewport, trigger a Livewire action to load the data.
                    @this.loadComments();
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