@props([
    'variant' => 'info',
])

@php
    $variantClass = match ($variant) {
        'success' => 'alert-success',
        'warning' => 'alert-warning',
        'error' => 'alert-error',
        default => 'alert-info',
    };
@endphp

<div role="alert" {{ $attributes->merge(['class' => "alert alert-soft {$variantClass} border border-base-300 py-2.5 text-sm"]) }}>
    <span>{{ $slot }}</span>
</div>
