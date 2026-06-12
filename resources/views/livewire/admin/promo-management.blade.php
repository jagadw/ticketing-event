<div class="space-y-6">
    <section class="grid gap-6 xl:grid-cols-[0.95fr_1.25fr]">
        <div class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $isEditMode ? 'Edit Promo' : 'Create Promo' }}</p>
                    <h3 class="mt-1 text-2xl font-semibold text-slate-950">{{ $isEditMode ? 'Edit promo kode' : 'Tambah promo baru' }}</h3>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Livewire Form</span>
            </div>

            <form wire:submit.prevent="savePromo" class="mt-6 space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Kode Promo</label>
                    <input 
                        wire:model="form.promo_code" 
                        type="text" 
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white"
                        placeholder="Contoh: DISKON50">
                    @error('form.promo_code') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Diskon (%)</label>
                        <input 
                            wire:model="form.discount_percentage" 
                            type="number" 
                            min="0" 
                            max="100" 
                            step="0.01"
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white"
                            placeholder="Contoh: 25.50">
                        @error('form.discount_percentage') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                        <label class="flex items-center gap-3 cursor-pointer">
                            <input wire:model="form.is_active" type="checkbox" class="rounded border-slate-300">
                            <span class="text-sm text-slate-700">Aktif</span>
                        </label>
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal Mulai</label>
                        <input 
                            wire:model="form.start_date" 
                            type="datetime-local" 
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                        @error('form.start_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal Akhir</label>
                        <input 
                            wire:model="form.end_date" 
                            type="datetime-local" 
                            class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                        @error('form.end_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="flex gap-3">
                    <button 
                        type="submit" 
                        class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">
                        {{ $isEditMode ? 'Update Promo' : 'Simpan Promo' }}
                    </button>
                    @if ($isEditMode)
                        <button 
                            wire:click="cancelEdit" 
                            type="button"
                            class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            Batal
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <div class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Promos Table</p>
                    <h3 class="mt-1 text-2xl font-semibold text-slate-950">Daftar promo</h3>
                </div>
                <div class="w-full sm:w-72">
                    <input 
                        wire:model="search" 
                        type="text" 
                        placeholder="Cari kode promo..." 
                        class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-medium">Kode Promo</th>
                            <th class="px-5 py-4 font-medium">Diskon</th>
                            <th class="px-5 py-4 font-medium">Tanggal Mulai</th>
                            <th class="px-5 py-4 font-medium">Tanggal Akhir</th>
                            <th class="px-5 py-4 font-medium">Status</th>
                            <th class="px-5 py-4 font-medium"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse ($this->filteredPromos as $promo)
                            <tr>
                                <td class="px-5 py-4">
                                    <div class="font-medium text-slate-950">{{ $promo['promo_code'] }}</div>
                                    <div class="text-xs text-slate-500">ID #{{ $promo['id'] }}</div>
                                </td>
                                <td class="px-5 py-4 text-slate-600">{{ number_format($promo['discount_percentage'], 2) }}%</td>
                                <td class="px-5 py-4 text-slate-600">{{ $promo['start_date'] }}</td>
                                <td class="px-5 py-4 text-slate-600">{{ $promo['end_date'] }}</td>
                                <td class="px-5 py-4">
                                    <button 
                                        wire:click="toggleActive({{ $promo['id'] }})"
                                        class="rounded-full px-3 py-1 text-xs font-medium transition cursor-pointer {{ $promo['is_active'] ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                        {{ $promo['is_active'] ? 'Aktif' : 'Nonaktif' }}
                                    </button>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <div class="flex items-center justify-end gap-3">
                                        <button 
                                            wire:click="editPromo({{ $promo['id'] }})"
                                            class="text-sm font-medium text-slate-500 transition hover:text-blue-600">
                                            Edit
                                        </button>
                                        <button 
                                            wire:click="deletePromo({{ $promo['id'] }})"
                                            class="text-sm font-medium text-slate-500 transition hover:text-red-600">
                                            Hapus
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-500">Tidak ada promo yang cocok dengan pencarian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>
