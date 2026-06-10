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

        <div class="rounded-[2rem] border border-slate-200/80 bg-slate-950 p-6 text-white shadow-[0_18px_50px_rgba(15,23,42,0.18)]">
            <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                <div class="max-w-xl space-y-3">
                    <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-400">Overview</p>
                    <h3 class="text-3xl font-semibold tracking-tight">Kontrol penuh untuk event, transaksi, dan pengguna dalam satu panel.</h3>
                    <p class="text-sm leading-6 text-slate-300">Tampilan ini dibuat minimalis, rapi, dan fokus ke data operasional. Semua halaman tetap konsisten secara visual dan siap dilanjutkan ke backend penuh.</p>
                </div>

                <div class="grid gap-3 sm:grid-cols-3 lg:w-[26rem]">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Open Tickets</p>
                        <p class="mt-2 text-2xl font-semibold">24</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Occupancy</p>
                        <p class="mt-2 text-2xl font-semibold">78%</p>
                    </div>
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-4">
                        <p class="text-xs uppercase tracking-[0.18em] text-slate-400">Active Promo</p>
                        <p class="mt-2 text-2xl font-semibold">6</p>
                    </div>
                </div>
            </div>
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
                <a href="{{ route('admin.events') }}" class="flex items-center justify-between rounded-2xl border border-slate-200 px-4 py-3 text-sm text-slate-700 transition hover:border-slate-300 hover:bg-slate-50">
                    <span>Buka management event</span>
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

        <div class="rounded-3xl border border-slate-200/80 bg-white/80 p-6 backdrop-blur">
            <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Activity Feed</p>
            <div class="mt-4 space-y-4">
                @foreach ($timeline as $item)
                    <article class="border-l-2 border-slate-200 pl-4">
                        <div class="flex items-center justify-between gap-3">
                            <h4 class="font-medium text-slate-950">{{ $item['title'] }}</h4>
                            <span class="text-xs text-slate-400">{{ $item['time'] }}</span>
                        </div>
                        <p class="mt-1 text-sm leading-6 text-slate-600">{{ $item['detail'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </aside>
</div>