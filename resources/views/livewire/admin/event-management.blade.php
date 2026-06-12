<div class="space-y-6">
    <section class="grid gap-6 xl:grid-cols-[0.95fr_1.25fr]">
        <div class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">{{ $isEditMode ? 'Edit Event' : 'Create Event' }}</p>
                    <h3 class="mt-1 text-2xl font-semibold text-slate-950">{{ $isEditMode ? 'Edit event' : 'Tambah event baru' }}</h3>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Livewire Form</span>
            </div>

            <form wire:submit.prevent="saveEvent" class="mt-6 space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Judul event</label>
                    <input wire:model="form.title" type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                    @error('form.title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Deskripsi</label>
                    <textarea wire:model="form.description" rows="4" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white"></textarea>
                    @error('form.description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Gambar Event</label>
                    <input wire:model="image" type="file" accept="image/*" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                    @error('image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    
                    @if ($image)
                        <div class="mt-3 rounded-2xl overflow-hidden border border-slate-200">
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full h-48 object-cover">
                        </div>
                    @endif
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Tanggal</label>
                        <input wire:model="form.event_date" type="datetime-local" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                        @error('form.event_date') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Lokasi</label>
                        <input wire:model="form.location" type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                        @error('form.location') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Harga tiket</label>
                        <input wire:model="form.ticket_price" type="number" min="0" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                        @error('form.ticket_price') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Kuota</label>
                        <input wire:model="form.quota" type="number" min="1" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                        @error('form.quota') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                    <select wire:model="form.status" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                        <option value="Draft">Draft</option>
                        <option value="Published">Published</option>
                    </select>
                    @error('form.status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="flex gap-3">
                    <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">
                        {{ $isEditMode ? 'Update Event' : 'Simpan Event' }}
                    </button>
                    @if ($isEditMode)
                        <button wire:click="cancelEdit" type="button" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-5 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                            Batal
                        </button>
                    @endif
                </div>
            </form>
        </div>

        <div class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Events Table</p>
                    <h3 class="mt-1 text-2xl font-semibold text-slate-950">Daftar event</h3>
                </div>
                <div class="w-full sm:w-72">
                    <input wire:model="search" type="text" placeholder="Cari event, lokasi, status..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
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
                                        <button wire:click="editEvent({{ $event['id'] }})" class="text-sm font-medium text-slate-500 transition hover:text-blue-600">Edit</button>
                                        <button wire:click="deleteEvent({{ $event['id'] }})" class="text-sm font-medium text-slate-500 transition hover:text-red-600">Hapus</button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-500">Tidak ada event yang cocok dengan pencarian.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>