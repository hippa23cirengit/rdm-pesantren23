@props([
    'label' => null,
    'name' => null,
    'type' => 'text',
    'hint' => null,
    'size' => 'sm',
    'required' => false,
])

@php
    $sizeClass = match ($size) {
        'xs' => 'input-xs',
        'md' => 'input-md',
        'lg' => 'input-lg',
        default => 'input-sm',
    };

    $errorKey = $name ?? $attributes->wire('model')->value();
    $hasError = isset($errors) && $errorKey && $errors->has($errorKey);
@endphp

<fieldset class="fieldset w-full">
    @if ($label)
        <legend class="fieldset-legend text-xs font-medium text-base-content">
            {{ $label }}
            @if ($required)
                <span class="text-error">*</span>
            @endif
        </legend>
    @endif

    <input
        type="{{ $type }}"
        @if ($name) name="{{ $name }}" @endif
        {{ $attributes->merge([
            'class' => trim('input input-bordered w-full ' . $sizeClass . ($hasError ? ' input-error' : '')),
        ]) }}
    />

    @if ($hasError)
        <p class="label text-xs text-error">{{ $errors->first($errorKey) }}</p>
    @elseif ($hint)
        <p class="label text-xs text-base-content/60">{{ $hint }}</p>
    @endif
</fieldset>
