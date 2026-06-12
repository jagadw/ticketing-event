<div class="space-y-6">
    <section class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Edit Promo</p>
                <h3 class="mt-1 text-2xl font-semibold text-slate-950">Edit: {{ $promo->promo_code }}</h3>
            </div>
            <a href="{{ route('admin.promos.index') }}" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-200">← Kembali</a>
        </div>

        <form wire:submit.prevent="updatePromo" class="space-y-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Kode Promo</label>
                <input wire:model="form.promo_code" type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                @error('form.promo_code') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Diskon (%)</label>
                <input wire:model="form.discount_percentage" type="number" min="0" max="100" step="1" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                @error('form.discount_percentage') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                    <input wire:model="form.start_date" type="datetime-local" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                    @error('form.start_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal Akhir</label>
                    <input wire:model="form.end_date" type="datetime-local" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                    @error('form.end_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div>
                <label class="flex items-center gap-3 cursor-pointer">
                    <input wire:model="form.is_active" type="checkbox" class="rounded border-slate-300">
                    <span class="text-sm font-medium text-slate-700">Aktif</span>
                </label>
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-blue-600 px-6 py-3 text-sm font-medium text-white transition hover:bg-blue-700">
                    Update Promo
                </button>
                <a href="{{ route('admin.promos.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-6 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </section>
</div>
