@props([
    'label' => null,
    'name' => null,
    'hint' => null,
    'size' => 'sm',
    'required' => false,
    'rows' => 3,
])

@php
    $sizeClass = match ($size) {
        'xs' => 'textarea-xs',
        'md' => 'textarea-md',
        'lg' => 'textarea-lg',
        default => 'textarea-sm',
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

    <textarea
        rows="{{ $rows }}"
        @if ($name) name="{{ $name }}" @endif
        {{ $attributes->merge([
            'class' => trim('textarea textarea-bordered w-full ' . $sizeClass . ($hasError ? ' textarea-error' : '')),
        ]) }}
    >{{ $slot }}</textarea>

    @if ($hasError)
        <p class="label text-xs text-error">{{ $errors->first($errorKey) }}</p>
    @elseif ($hint)
        <p class="label text-xs text-base-content/60">{{ $hint }}</p>
    @endif
</fieldset>
