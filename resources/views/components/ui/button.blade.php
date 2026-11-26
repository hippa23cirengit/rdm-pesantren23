@props([
    'variant' => 'primary',
    'size' => 'sm',
    'type' => 'button',
    'href' => null,
    'outline' => false,
])

@php
    $variantClasses = match ($variant) {
        'secondary' => 'btn-secondary',
        'accent' => 'btn-accent',
        'neutral' => 'btn-neutral',
        'ghost' => 'btn-ghost',
        'info' => 'btn-info',
        'success' => 'btn-success',
        'warning' => 'btn-warning',
        'error' => 'btn-error',
        default => 'btn-primary',
    };

    $sizeClasses = match ($size) {
        'xs' => 'btn-xs',
        'md' => 'btn-md',
        'lg' => 'btn-lg',
        default => 'btn-sm',
    };

    $classes = trim(implode(' ', array_filter([
        'btn',
        $variantClasses,
        $sizeClasses,
        $outline ? 'btn-outline' : null,
    ])));
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
