<div class="space-y-6">
    <section class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
        <div class="flex items-start justify-between gap-4 mb-6">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Create Event</p>
                <h3 class="mt-1 text-2xl font-semibold text-slate-950">Tambah event baru</h3>
            </div>
            <a href="{{ route('admin.events.index') }}" class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600 transition hover:bg-slate-200">Kembali</a>
        </div>

        @if (session('success'))
            <div class="mb-4 rounded-2xl bg-emerald-50 border border-emerald-200 p-4 text-sm text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-4 rounded-2xl bg-red-50 border border-red-200 p-4 text-sm text-red-700">
                {{ session('error') }}
            </div>
        @endif

        <form wire:submit.prevent="saveEvent" class="space-y-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Judul event</label>
                <input wire:model="form.title" type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white" placeholder="Contoh: Workshop Design">
                @error('form.title') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Deskripsi</label>
                <textarea wire:model="form.description" rows="4" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white" placeholder="Jelaskan detail event..."></textarea>
                @error('form.description') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-slate-700">Gambar Event</label>
                <input wire:model="image" type="file" accept="image/*" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                <p class="mt-2 text-xs text-slate-500">Max 2MB, format: JPG, PNG, GIF, WebP</p>
                @error('image') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                
                @if ($image)
                    <div class="mt-3 rounded-2xl overflow-hidden border border-slate-200">
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full max-h-64 object-cover">
                        <p class="mt-2 text-xs text-slate-500 p-2">Nama file: {{ $image->getClientOriginalName() }}</p>
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
                    <input wire:model="form.location" type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white" placeholder="Jakarta Convention Center">
                    @error('form.location') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Harga tiket (Rp)</label>
                    <input wire:model="form.ticket_price" type="number" min="0" step="1000" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white" placeholder="250000">
                    @error('form.ticket_price') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Kuota</label>
                    <input wire:model="form.quota" type="number" min="1" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white" placeholder="100">
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

            <div class="flex gap-3 pt-4">
                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-emerald-600 px-6 py-3 text-sm font-medium text-white transition hover:bg-emerald-700">
                    Simpan Event
                </button>
                <a href="{{ route('admin.events.index') }}" class="inline-flex items-center justify-center rounded-2xl border border-slate-300 px-6 py-3 text-sm font-medium text-slate-700 transition hover:bg-slate-50">
                    Batal
                </a>
            </div>
        </form>
    </section>
</div>
