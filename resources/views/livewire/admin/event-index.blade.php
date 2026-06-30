<div class="space-y-6">
    <section class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Events</p>
                <h3 class="mt-1 text-2xl font-semibold text-slate-950">Daftar event</h3>
            </div>
            <div class="flex gap-3 flex-wrap">
                <div class="flex-1 sm:flex-none">
                    {{-- <input wire:model="search" type="text" placeholder="Cari event, lokasi, status..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white"> --}}
                </div>
                <a href="{{ route('admin.events.create') }}" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-medium text-white transition hover:bg-emerald-700">
                    + Tambah Event
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="mt-4 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mt-4 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <div class="mt-6 overflow-x-auto rounded-3xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-5 py-4 font-medium">Gambar</th>
                        <th class="px-5 py-4 font-medium">Event</th>
                        <th class="px-5 py-4 font-medium">Tanggal</th>
                        <th class="px-5 py-4 font-medium">Lokasi</th>
                        <th class="px-5 py-4 font-medium">Harga</th>
                        <th class="px-5 py-4 font-medium">Kuota</th>
                        <th class="px-5 py-4 font-medium">Status</th>
                        <th class="px-5 py-4 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($this->filteredEvents as $event)
                        <tr>
                            <td class="px-5 py-4">
                                @if ($event['image'])
                                    <div class="w-16 h-16 rounded-lg overflow-hidden bg-slate-100 border border-slate-200">
                                        <img src="{{ asset('storage/' . $event['image']) }}" alt="{{ $event['title'] }}" class="w-full h-full object-cover">
                                    </div>
                                @else
                                    <div class="w-16 h-16 rounded-lg bg-slate-100 border border-slate-200 flex items-center justify-center text-xs text-slate-400">
                                        No Image
                                    </div>
                                @endif
                            </td>
                            <td class="px-5 py-4">
                                <div class="font-medium text-slate-950">{{ $event['title'] }}</div>
                                <div class="text-xs text-slate-500">ID #{{ $event['id'] }}</div>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $event['event_date'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $event['location'] }}</td>
                            <td class="px-5 py-4 text-slate-600">Rp {{ number_format($event['ticket_price'], 0, ',', '.') }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $event['quota'] }}</td>
                            <td class="px-5 py-4">
                                <button wire:click="toggleStatus({{ $event['id'] }})" class="rounded-full px-3 py-1 text-xs font-medium transition cursor-pointer {{ $event['status'] === 'Published' ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                                    {{ $event['status'] }}
                                </button>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('admin.events.edit', $event['id']) }}" class="text-sm font-medium text-slate-500 transition hover:text-blue-600">Edit</a>
                                    <button wire:click="deleteEvent({{ $event['id'] }})" onclick="return confirm('Apakah anda yakin?')" class="text-sm font-medium text-slate-500 transition hover:text-red-600">Hapus</button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-slate-500">Tidak ada event yang cocok dengan pencarian.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
