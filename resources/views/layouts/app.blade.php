@props([
    'title' => 'RDM Internal MTs Persis 23',
    'activePeriod' => 'Belum dikonfigurasi',
])

<!DOCTYPE html>
<html lang="id" data-theme="rdm">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — RDM MTs Persis 23</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-base-200 text-base-content antialiased">
    <div class="drawer lg:drawer-open">
        <input id="rdm-sidebar" type="checkbox" class="drawer-toggle" />

        <div class="drawer-content flex min-h-screen flex-col">
            <header class="navbar min-h-14 border-b border-base-300 bg-base-100 px-4">
                <div class="flex flex-1 items-center gap-3">
                    <label for="rdm-sidebar" class="btn btn-ghost btn-sm btn-square lg:hidden" aria-label="Buka menu navigasi">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </label>
                    <h1 class="text-sm font-semibold text-base-content">{{ $title }}</h1>
                </div>
                <div class="flex items-center gap-2">
                    <x-ui.badge variant="primary">Periode: {{ $activePeriod }}</x-ui.badge>
                    @isset($headerActions)
                        {{ $headerActions }}
                    @endisset
                </div>
            </header>

            <main class="flex-1 p-4 lg:p-6">
                {{ $slot }}
            </main>
        </div>

        <aside class="drawer-side z-30">
            <label for="rdm-sidebar" aria-label="Tutup menu navigasi" class="drawer-overlay"></label>
            <div class="flex min-h-full w-64 flex-col border-r border-base-300 bg-base-100">
                <div class="flex h-14 items-center border-b border-base-300 px-4">
                    <div>
                        <p class="text-sm font-bold tracking-tight text-primary">RDM Internal</p>
                        <p class="text-xs text-base-content/60">MTs Persis 23</p>
                    </div>
                </div>

                <nav class="menu w-full flex-1 gap-1 p-3 text-sm" aria-label="Navigasi Utama">
                    @isset($sidebar)
                        {{ $sidebar }}
                    @else
                        <li><a href="{{ url('/') }}" class="active">Beranda</a></li>
                    @endisset
                </nav>

                @isset($sidebarFooter)
                    <div class="border-t border-base-300 p-3 text-xs text-base-content/70">
                        {{ $sidebarFooter }}
                    </div>
                @endisset
            </div>
        </aside>
    </div>

    @livewireScripts
</body>
</html>
