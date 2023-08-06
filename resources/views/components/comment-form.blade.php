@props(['parent'=>null])
@auth
<div class="pb-4">
    <div class="input-group">
        <input type="text" class="form-control @error('comment') is-invalid @enderror form-control-alt" wire:model.lazy="comment" placeholder="Write a comment..." style="border-top-right-radius: 0; border-bottom-right-radius:0;">
        <div class="input-group-append">
            <button class="btn btn-alt-info" style="border-top-left-radius: 0; border-bottom-left-radius:0;" type="button" wire:click="addComment({{ $parent }})">Post</button>
        </div>
        @error('comment')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
@endauth