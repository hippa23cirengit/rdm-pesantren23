@props([
    'label' => null,
    'name' => null,
    'hint' => null,
    'size' => 'sm',
    'required' => false,
    'placeholder' => null,
])

@php
    $sizeClass = match ($size) {
        'xs' => 'select-xs',
        'md' => 'select-md',
        'lg' => 'select-lg',
        default => 'select-sm',
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

    <select
        @if ($name) name="{{ $name }}" @endif
        {{ $attributes->merge([
            'class' => trim('select select-bordered w-full ' . $sizeClass . ($hasError ? ' select-error' : '')),
        ]) }}
    >
        @if ($placeholder)
            <option value="">{{ $placeholder }}</option>
        @endif
        {{ $slot }}
    </select>

    @if ($hasError)
        <p class="label text-xs text-error">{{ $errors->first($errorKey) }}</p>
    @elseif ($hint)
        <p class="label text-xs text-base-content/60">{{ $hint }}</p>
    @endif
</fieldset>
