@props([
    'size' => 'sm',
    'zebra' => false,
    'pinRows' => false,
])

@php
    $sizeClass = match ($size) {
        'xs' => 'table-xs',
        'md' => 'table-md',
        'lg' => 'table-lg',
        default => 'table-sm',
    };

    $classes = trim(implode(' ', array_filter([
        'table w-full',
        $sizeClass,
        $zebra ? 'table-zebra' : null,
        $pinRows ? 'table-pin-rows' : null,
    ])));
@endphp

<div class="overflow-x-auto rounded-box border border-base-300 bg-base-100">
    <table {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </table>
</div>
