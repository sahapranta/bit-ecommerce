@props(['comment', 'child'=>false])
<div class="d-flex @if ($child) mt-3 @else push @endif" wire:key="comment-{{ $comment['id'] }}">
    <a class="flex-shrink-0 me-3" href="javascript:void(0)">
        <img class="img-avatar img-avatar32" src="{{ $comment->user->avatar }}" alt="Avatar">
    </a>
    <div class="flex-grow-1">
        <a class="fw-semibold" href="javascript:void(0)">{{ $comment->user->name }}</a>
        <x-mark :mark="$comment->mark" />
        <p class="my-1">{{ $comment->comment }}</p>
        <!-- <a class="me-1" href="javascript:void(0)">Like</a> -->
        <span class="text-muted"><em>{{ $comment->created_at->diffForHumans() }}</em></span>
        @if (false)
        <a class="me-1 reply" onclick="reply()" href="javascript:void(0)">Reply</a>
        <x-reply-form :parent="$comment->id" />
            @if ($comment->replies->count())
                @foreach ($comment->replies as $reply)
                <x-comment :comment="$reply" :child="true" />
                @endforeach
            @endif
        @endif
    </div>
</div>