<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login {{ config('app.name', 'EVENT4U') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="min-h-screen bg-[#f5f6f2] text-slate-900 antialiased">
<div class="relative flex min-h-screen">
    <div class="mx-auto my-auto w-full max-w-md rounded-2xl border border-slate-200/80 bg-white/70 p-8 shadow-lg backdrop-blur">
        <div class="mb-6">
            <p class="text-sm font-semibold tracking-[0.18em] text-slate-500 uppercase text-center">Admin EVENT4U</p>
            <h1 class="mt-1 text-xl font-semibold text-slate-950 text-center">LOG IN</h1>
        </div>

        @if (session('error'))
            <div class="mb-4 rounded-xl bg-red-50 px-4 py-3 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login.post') }}" class="space-y-4">
            @csrf
            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="email">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                       class="w-full rounded-xl border border-slate-200/80 bg-white/80 px-3 py-2 text-sm outline-none focus:border-slate-900/30 focus:ring-0" />
                @error('email')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="mb-1 block text-sm font-medium text-slate-700" for="password">Password</label>
                <input id="password" name="password" type="password" required
                       class="w-full rounded-xl border border-slate-200/80 bg-white/80 px-3 py-2 text-sm outline-none focus:border-slate-900/30 focus:ring-0" />
                @error('password')
                <p class="mt-2 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                    class="w-full rounded-xl bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 hover:bg-slate-800 transition">
                Login
            </button>
        </form>
    </div>
</div>

@livewireScripts
</body>
</html>

