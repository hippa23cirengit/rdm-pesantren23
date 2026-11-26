@props([
    'variant' => 'neutral',
    'size' => 'sm',
    'soft' => true,
])

@php
    $variantClass = match ($variant) {
        'primary' => 'badge-primary',
        'secondary' => 'badge-secondary',
        'accent' => 'badge-accent',
        'info' => 'badge-info',
        'success' => 'badge-success',
        'warning' => 'badge-warning',
        'error' => 'badge-error',
        default => 'badge-neutral',
    };

    $sizeClass = match ($size) {
        'xs' => 'badge-xs',
        'md' => 'badge-md',
        'lg' => 'badge-lg',
        default => 'badge-sm',
    };

    $classes = trim(implode(' ', array_filter([
        'badge font-medium',
        $variantClass,
        $sizeClass,
        $soft ? 'badge-soft' : null,
    ])));
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
