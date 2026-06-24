<div class="space-y-6">
    <section class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Promo</p>
                <h3 class="mt-1 text-2xl font-semibold text-slate-950">Daftar promo</h3>
            </div>
            <div class="flex gap-3 flex-wrap">
                <div class="flex-1 sm:flex-none">
                    <input wire:model="search" type="text" placeholder="Cari kode promo..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                </div>
                <a href="{{ route('admin.promos.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-emerald-700">
                    + Tambah Promo
                </a>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto rounded-3xl border border-slate-200">
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
                            <td class="px-5 py-4 text-slate-600">{{ (int)$promo['discount_percentage'] }}%</td>
                            <td class="px-5 py-4 text-slate-600">{{ $promo['start_date'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $promo['end_date'] }}</td>
                            <td class="px-5 py-4">
                                <button wire:click="toggleActive({{ $promo['id'] }})" class="rounded-full px-3 py-1 text-xs font-medium transition cursor-pointer {{ $promo['is_active'] ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                                    {{ $promo['is_active'] ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.promos.edit', $promo['id']) }}" class="text-sm font-medium text-slate-500 transition hover:text-blue-600">Edit</a>
                                    <button wire:click="deletePromo({{ $promo['id'] }})" onclick="return confirm('Apakah anda yakin?')" class="text-sm font-medium text-slate-500 transition hover:text-red-600">Hapus</button>
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
    </section>
</div>
