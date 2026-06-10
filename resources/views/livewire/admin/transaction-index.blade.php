<div class="space-y-6">
    <section class="rounded-[2rem] border border-slate-200/80 bg-white/85 p-6 shadow-[0_12px_30px_rgba(15,23,42,0.04)] backdrop-blur">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500">Transactions</p>
                <h3 class="mt-1 text-2xl font-semibold text-slate-950">Daftar transaksi</h3>
                <p class="mt-2 text-sm text-slate-600">Pantau status pembayaran, metode, dan total order dari satu layar.</p>
            </div>

            <div class="flex w-full flex-col gap-3 sm:flex-row lg:w-auto">
                <input wire:model="search" type="text" placeholder="Cari kode, user, event..." class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white sm:w-80">
                <select wire:model="statusFilter" class="w-full rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm outline-none transition focus:border-slate-400 focus:bg-white sm:w-44">
                    <option value="all">Semua status</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="cancelled">Cancelled</option>
                </select>
            </div>
        </div>

        <div class="mt-6 overflow-hidden rounded-3xl border border-slate-200">
            <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                <thead class="bg-slate-50 text-slate-500">
                    <tr>
                        <th class="px-5 py-4 font-medium">Kode</th>
                        <th class="px-5 py-4 font-medium">User</th>
                        <th class="px-5 py-4 font-medium">Event</th>
                        <th class="px-5 py-4 font-medium">Qty</th>
                        <th class="px-5 py-4 font-medium">Total</th>
                        <th class="px-5 py-4 font-medium">Status</th>
                        <th class="px-5 py-4 font-medium">Metode</th>
                        <th class="px-5 py-4 font-medium">Waktu</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse ($this->filteredTransactions as $transaction)
                        <tr>
                            <td class="px-5 py-4 font-medium text-slate-950">{{ $transaction['id'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $transaction['user'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $transaction['event'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $transaction['quantity'] }}</td>
                            <td class="px-5 py-4 text-slate-600">Rp {{ number_format($transaction['total'], 0, ',', '.') }}</td>
                            <td class="px-5 py-4">
                                <span class="rounded-full px-3 py-1 text-xs font-medium {{ $transaction['status'] === 'paid' ? 'bg-emerald-50 text-emerald-700' : ($transaction['status'] === 'pending' ? 'bg-amber-50 text-amber-700' : 'bg-rose-50 text-rose-700') }}">{{ ucfirst($transaction['status']) }}</span>
                            </td>
                            <td class="px-5 py-4 text-slate-600">{{ $transaction['method'] }}</td>
                            <td class="px-5 py-4 text-slate-600">{{ $transaction['date'] }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-slate-500">Tidak ada transaksi yang cocok.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>