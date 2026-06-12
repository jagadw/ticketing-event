<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title ?? config('app.name', 'Ticket Admin') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-[#f5f6f2] text-slate-900 antialiased">
    <div class="pointer-events-none fixed inset-0 overflow-hidden">
        <div class="absolute -top-24 right-0 h-72 w-72 rounded-full bg-emerald-200/40 blur-3xl"></div>
        <div class="absolute left-[-6rem] top-1/3 h-80 w-80 rounded-full bg-slate-200/70 blur-3xl"></div>
    </div>

    <div class="relative flex min-h-screen">
        <aside class="hidden w-72 shrink-0 border-r border-slate-200/80 bg-white/70 backdrop-blur xl:flex xl:flex-col">
            <div class="border-b border-slate-200/80 p-6">
                <div class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-slate-900 text-sm font-semibold text-white">TA</div>
                    <div>
                        <p class="text-sm font-semibold tracking-[0.18em] text-slate-500 uppercase">Ticket Admin</p>
                        <h1 class="text-lg font-semibold text-slate-950">Modern Minimal Panel</h1>
                    </div>
                </div>
            </div>

            <nav class="flex-1 p-4">
                <div class="space-y-2">
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/15' : 'text-slate-600 hover:bg-slate-100' }} flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition">
                        <span>Dashboard</span>
                        <span class="text-xs opacity-70">01</span>
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="{{ request()->routeIs('admin.events.*') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/15' : 'text-slate-600 hover:bg-slate-100' }} flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition">
                        <span>Management Event</span>
                        <span class="text-xs opacity-70">02</span>
                    </a>
                    <a href="{{ route('admin.transactions') }}" class="{{ request()->routeIs('admin.transactions') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/15' : 'text-slate-600 hover:bg-slate-100' }} flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition">
                        <span>Daftar Transaksi</span>
                        <span class="text-xs opacity-70">03</span>
                    </a>
                    <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users') ? 'bg-slate-900 text-white shadow-lg shadow-slate-900/15' : 'text-slate-600 hover:bg-slate-100' }} flex items-center justify-between rounded-2xl px-4 py-3 text-sm font-medium transition">
                        <span>Management Pengguna</span>
                        <span class="text-xs opacity-70">04</span>
                    </a>
                </div>

                <div class="mt-8 rounded-3xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">System Status</p>
                    <div class="mt-4 space-y-3 text-sm text-slate-600">
                        <div class="flex items-center justify-between"><span>Server</span><span class="font-medium text-emerald-600">Healthy</span></div>
                        <div class="flex items-center justify-between"><span>Orders</span><span class="font-medium text-slate-900">Realtime</span></div>
                        <div class="flex items-center justify-between"><span>Users</span><span class="font-medium text-slate-900">Synced</span></div>
                    </div>
                </div>
            </nav>
        </aside>

        <div class="flex min-h-screen flex-1 flex-col">
            <header class="border-b border-slate-200/80 bg-white/70 backdrop-blur">
                <div class="mx-auto flex max-w-7xl items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-500">Admin Panel</p>
                        <h2 class="text-xl font-semibold text-slate-950">{{ $title ?? 'Dashboard Admin' }}</h2>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="hidden rounded-full border border-slate-200 bg-white px-4 py-2 text-sm text-slate-500 shadow-sm sm:block">Livewire UI</div>
                        <div class="flex h-11 w-11 items-center justify-center rounded-full bg-slate-900 text-sm font-semibold text-white">A</div>
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