<div class="space-y-6">
    <section class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Users Table</p>
                <h3 class="mt-1 text-2xl font-semibold text-slate-950">Daftar pengguna</h3>
            </div>
            <div class="w-full sm:w-72">
                {{-- <input wire:model="search" type="text" placeholder="Cari nama, email, status..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white"> --}}
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-5 py-4 font-medium">Nama</th>
                        <th class="px-5 py-4 font-medium">Email</th>
                        <th class="px-5 py-4 font-medium">Status</th>
                        <th class="px-5 py-4 font-medium"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($this->filteredUsers as $user)
                        <tr>
                            <td class="px-5 py-4 font-medium text-slate-950">{{ $user['name'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $user['email'] }}</td>
                            <td class="px-5 py-4">
                                <button wire:click="toggleStatus({{ $user['id'] }})" class="rounded-full px-3 py-1 text-xs font-medium transition cursor-pointer {{ $user['status'] === 'Active' ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' : 'bg-amber-50 text-amber-700 hover:bg-amber-100' }}">
                                    {{ $user['status'] }}
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-10 text-center text-slate-500">Tidak ada pengguna yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>