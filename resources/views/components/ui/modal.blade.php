@props([
    'id',
    'title' => null,
])

<dialog id="{{ $id }}" {{ $attributes->merge(['class' => 'modal']) }}>
    <div class="modal-box max-w-lg border border-base-300 bg-base-100 p-5">
        @if ($title)
            <h3 class="border-b border-base-300 pb-3 text-base font-semibold text-base-content">
                {{ $title }}
            </h3>
        @endif

        <div class="py-3">
            {{ $slot }}
        </div>

        @isset($actions)
            <div class="modal-action mt-2 border-t border-base-300 pt-3">
                {{ $actions }}
            </div>
        @endisset
    </div>
    <form method="dialog" class="modal-backdrop">
        <button aria-label="Tutup dialog">Tutup</button>
    </form>
</dialog>
