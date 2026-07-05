<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public array $stats = [];

    public function mount(): void
    {
        $totalEventAktif = \App\Models\events::query()
            ->where('status', 'Active')
            ->count();

        $transaksiPending = \App\Models\transactions::query()
            ->where('status', 'pending')
            ->count();

        $penggunaTerdaftar = \App\Models\User::query()
            ->count();

        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();

        $transactionsThisMonth = \App\Models\transactions::query()
            ->where('status', 'paid')
            ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->get();

        $revenue = $transactionsThisMonth->sum('total_price');
        $ordersCount = $transactionsThisMonth->count();

        $this->stats = [
            ['label' => 'Total Event Aktif', 'value' => $totalEventAktif, 'note' => 'Jumlah event aktif'],
            ['label' => 'Transaksi Pending', 'value' => $transaksiPending, 'note' => 'Jumlah transaksi pending'],
            ['label' => 'Pengguna Terdaftar', 'value' => $penggunaTerdaftar, 'note' => 'Pengguna baru bulan ini'],
            [
                'label' => 'Revenue Bulan Ini',
                'value' => 'Rp ' . number_format($revenue / 1000000, 1, ',', '.') . ' jt',
                'note' => 'Dari ' . $ordersCount . ' order',
            ],
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard', ['title' => 'Dashboard Admin']);
    }
}

