@props(['parent'=>null])
@auth
<div class="pb-4 d-none reply-form">
    <div class="input-group">
        <input type="text" class="form-control @error('reply') is-invalid @enderror form-control-alt" wire:model.defer="reply" placeholder="Write a reply..." style="border-top-right-radius: 0; border-bottom-right-radius:0;">
        <div class="input-group-append">
            <button class="btn btn-alt-info" style="border-top-left-radius: 0; border-bottom-left-radius:0;" type="button" wire:click="addReply({{ $parent }})">Post</button>
        </div>
        @error('reply')
        <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
</div>
@endauth