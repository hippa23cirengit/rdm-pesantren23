@props([
    'title' => null,
    'subtitle' => null,
])

<div {{ $attributes->merge(['class' => 'card border border-base-300 bg-base-100']) }}>
    <div class="card-body gap-3 p-4">
        @if ($title || isset($actions))
            <div class="flex flex-wrap items-center justify-between gap-2 border-b border-base-300 pb-3">
                <div>
                    @if ($title)
                        <h2 class="text-sm font-semibold text-base-content">{{ $title }}</h2>
                    @endif
                    @if ($subtitle)
                        <p class="text-xs text-base-content/60">{{ $subtitle }}</p>
                    @endif
                </div>
                @isset($actions)
                    <div class="flex items-center gap-2">
                        {{ $actions }}
                    </div>
                @endisset
            </div>
        @endif

        {{ $slot }}
    </div>
</div>
