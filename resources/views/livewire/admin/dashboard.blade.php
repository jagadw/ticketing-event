<div class="grid gap-6 xl:grid-cols-[1.45fr_0.95fr]">
    <section class="space-y-6">
        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ($stats as $stat)
                <article class="rounded-3xl border border-slate-200/80 bg-white/80 p-5 shadow-[0_10px_30px_rgba(15,23,42,0.04)] backdrop-blur">
                    <p class="text-sm text-slate-500">{{ $stat['label'] }}</p>
                    <div class="mt-3 text-3xl font-semibold tracking-tight text-slate-950">{{ $stat['value'] }}</div>
                    <p class="mt-2 text-sm text-emerald-700">{{ $stat['note'] }}</p>
                </article>
            @endforeach
        </div>
    </section>

    <aside class="space-y-6">
        <div class="rounded-3xl border border-slate-200/80 bg-white/80 p-6 backdrop-blur">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Quick Access</p>
                    <h3 class="mt-1 text-lg font-semibold text-slate-950">Kelola data utama</h3>
                </div>
                <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-700">Ready</span>
            </div>

            <div class="mt-5 space-y-3">
                <a href="{{ route('admin.events.index') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    <span>Buka management event</span>
                    <span>→</span>
                </a>
                <a href="{{ route('admin.promos') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    <span>Kelola kode promo</span>
                    <span>→</span>
                </a>
                <a href="{{ route('admin.transactions') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    <span>Review transaksi masuk</span>
                    <span>→</span>
                </a>
                <a href="{{ route('admin.users') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    <span>Atur akses pengguna</span>
                    <span>→</span>
                </a>
            </div>
        </div>
    </aside>
</div>