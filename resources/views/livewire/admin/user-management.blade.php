<div class="space-y-6">
    <section class="grid gap-6 xl:grid-cols-[0.95fr_1.25fr]">
        <div class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">User Access</p>
                    <h3 class="mt-1 text-2xl font-semibold text-slate-950">Tambah pengguna</h3>
                </div>
                <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-600">Role Manager</span>
            </div>

            <form wire:submit.prevent="saveUser" class="mt-6 space-y-4">
                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Nama</label>
                    <input wire:model="form.name" type="text" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                    @error('form.name') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="mb-2 block text-sm font-medium text-slate-700">Email</label>
                    <input wire:model="form.email" type="email" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                    @error('form.email') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Role</label>
                        <select wire:model="form.role" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                            <option value="user">User</option>
                            <option value="admin">Admin</option>
                        </select>
                        @error('form.role') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                        <select wire:model="form.status" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                            <option value="Active">Active</option>
                            <option value="Suspended">Suspended</option>
                        </select>
                        @error('form.status') <p class="mt-2 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                <button type="submit" class="inline-flex items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-medium text-white transition hover:bg-slate-800">
                    Simpan Pengguna
                </button>
            </form>
        </div>

        <div class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Users Table</p>
                    <h3 class="mt-1 text-2xl font-semibold text-slate-950">Daftar pengguna</h3>
                </div>
                <div class="w-full sm:w-72">
                    <input wire:model="search" type="text" placeholder="Cari nama, email, role..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white">
                </div>
            </div>

            <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
                <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                    <thead class="bg-slate-50 text-slate-500">
                        <tr>
                            <th class="px-5 py-4 font-medium">Nama</th>
                            <th class="px-5 py-4 font-medium">Email</th>
                            <th class="px-5 py-4 font-medium">Role</th>
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
                                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-medium text-slate-700">{{ strtoupper($user['role']) }}</span>
                                </td>
                                <td class="px-5 py-4">
                                    <span class="rounded-full px-3 py-1 text-xs font-medium {{ $user['status'] === 'Active' ? 'bg-emerald-50 text-emerald-700' : 'bg-amber-50 text-amber-700' }}">{{ $user['status'] }}</span>
                                </td>
                                <td class="px-5 py-4 text-right">
                                    <button wire:click="deleteUser({{ $user['id'] }})" class="text-sm font-medium text-slate-500 transition hover:text-red-600">Hapus</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-5 py-10 text-center text-slate-500">Tidak ada pengguna yang cocok.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </section>
</div>