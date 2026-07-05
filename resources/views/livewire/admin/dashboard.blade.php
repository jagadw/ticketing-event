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
</div>