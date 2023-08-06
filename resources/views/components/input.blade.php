@props(['name', 'id', 'label', 'placeholder', 'feedback'=>null, 'info'=>null, 'required'=>false, 'value'=>'', 'disabled' => false])

<div class="mb-4">
    <label class="form-label text-capitalize" for="{{ $id ?? $name }}">{{$label ?? Str::of($name)->replace('_', ' ')->title() }} {!! $required?'<span class="text-danger">*</span>':'' !!}
        @if ($info)
            <i class="fas fa-question-circle text-muted" data-bs-toggle="tooltip" data-bs-position="top" title="{{ $info }}"></i>
        @endif
    </label>
    <input
    {{ $attributes->merge([
        'class' => 'form-control ' . ($errors->has($name) ? 'is-invalid' : ''),
        'id' => $id ?? $name,
        'name' => $name,
        'value' => old($name, $value),
        'required' => $required,
        'disabled' => $disabled,
        'placeholder' => $placeholder ?? 'Write ' . $name . '...',
        'aria-describedby' => $id ?? $name . '-feedback',
        'type' => 'text',
        ]) }}
    >
    @if($feedback != null)
    <small class="text-small feedback text-muted">{{ $feedback }}</small>
    @endif
    @if ($slot != null)
        {{ $slot }}
    @endif
    @error($name)
    <div class="invalid-feedback">
        {{ $message }}
    </div>
    @enderror
</div>