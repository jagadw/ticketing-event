<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'EVENT4U') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-[#f5f6f2] text-slate-900 antialiased">

    <div class="relative flex min-h-screen">
        <aside class="hidden w-72 shrink-0 border-r border-slate-200/80 bg-white/70 backdrop-blur xl:flex xl:flex-col">
            <div class="border-b border-slate-200/80 p-6">
                <div class="flex items-center gap-3">
                    {{-- <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-sm font-semibold text-white">TA</div> --}}
                    <div>
                        <p class="text-sm font-semibold tracking-[0.18em] text-slate-500 uppercase">Admin Dashboard</p>
                        <h1 class="text-lg font-semibold text-slate-950">EVENT4U</h1>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-4">
                <div class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/15' : 'text-slate-600 hover:bg-slate-100' }} flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition">
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/15' : 'text-slate-600 hover:bg-slate-100' }} flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition">
                        <span>Management Event</span>
                    </a>
                    <a href="{{ route('admin.promos.index') }}" class="{{ request()->routeIs('admin.promos.*') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/15' : 'text-slate-600 hover:bg-slate-100' }} flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition">
                        <span>Management Promo</span>
                    </a>
                    <a href="{{ route('admin.transactions') }}" class="{{ request()->routeIs('admin.transactions') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/15' : 'text-slate-600 hover:bg-slate-100' }} flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition">
                        <span>Daftar Transaksi</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/15' : 'text-slate-600 hover:bg-slate-100' }} flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition">
                        <span>Management Pengguna</span>
                    </a>
                </div>

            </nav>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-slate-200/80 bg-white/70 backdrop-blur">
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                    <div>
                        <h2 class="text-xl font-semibold text-slate-950">{{ $title ?? 'Admin Dashboard' }}</h2>
                    </div>

                    <div class="flex items-center gap-3">
                        @php
                            $admin = auth()->user();
                            $initial = $admin ? strtoupper(substr($admin->name ?? $admin->email ?? 'A', 0, 1)) : 'A';
                        @endphp

                        <div class="relative">
                            <button type="button" id="adminAvatarBtn" class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">
                                {{ $initial }}
                            </button>

                            <div id="adminAvatarMenu" class="absolute right-0 mt-2 hidden w-56 rounded-2xl border border-slate-200/80 bg-white/90 shadow-lg backdrop-blur">
                                <div class="px-4 py-3">
                                    <p class="text-sm font-semibold text-slate-950">{{ $admin->name ?? $admin->email }}</p>
                                    <p class="text-xs text-slate-500">{{ $admin->email ?? '' }}</p>
                                </div>

                                <div class="border-t border-slate-200/80 px-3 py-2">
                                    <form method="POST" action="{{ route('admin.logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full rounded-xl bg-slate-900 px-3 py-2 text-sm font-semibold text-white hover:bg-slate-800">
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <script>
                            (function () {
                                const btn = document.getElementById('adminAvatarBtn');
                                const menu = document.getElementById('adminAvatarMenu');
                                if (!btn || !menu) return;

                                const toggle = () => menu.classList.toggle('hidden');
                                btn.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    toggle();
                                });

                                document.addEventListener('click', function (e) {
                                    if (!menu.classList.contains('hidden') && !menu.contains(e.target) && !btn.contains(e.target)) {
                                        menu.classList.add('hidden');
                                    }
                                });
                            })();
                        </script>
                    </div>


                </div>
            </header>
            <main class="mx-auto w-full max-w-7xl flex-1 px-4 py-6 sm:px-6 lg:px-8 lg:py-8">
                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
</body>
</html>