<?php

namespace App\Livewire\Admin;

use App\Models\events;
use App\Models\transactions;
use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    protected $listeners = ['$refresh'];

    public function getStatsProperty(): array
    {
        $totalEvents   = events::where('status', 'Published')->count();
        $pendingTx     = transactions::where('status', 'pending')->count();
        $totalUsers    = User::where('role', 'user')->count();
        $revenueMonth  = transactions::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->whereYear('paid_at', now()->year)
            ->sum('total');
        $orderCount    = transactions::where('status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->count();

        return [
            [
                'label' => 'Total Event Aktif',
                'value' => $totalEvents,
                'note'  => 'Event Published',
                'icon'  => 'calendar',
                'color' => 'blue',
            ],
            [
                'label' => 'Transaksi Pending',
                'value' => $pendingTx,
                'note'  => $pendingTx > 0 ? 'Perlu follow up' : 'Semua sudah diproses',
                'icon'  => 'clock',
                'color' => $pendingTx > 0 ? 'orange' : 'green',
            ],
            [
                'label' => 'Pengguna Terdaftar',
                'value' => $totalUsers,
                'note'  => 'Total user aktif',
                'icon'  => 'users',
                'color' => 'purple',
            ],
            [
                'label' => 'Revenue Bulan Ini',
                'value' => 'Rp ' . number_format($revenueMonth, 0, ',', '.'),
                'note'  => "Dari {$orderCount} order",
                'icon'  => 'currency',
                'color' => 'green',
            ],
        ];
    }

    public function getRecentTransactionsProperty()
    {
        return transactions::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }

    public function getTimelineProperty(): array
    {
        $items = [];

        $latestTx = transactions::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        foreach ($latestTx as $tx) {
            $statusLabel = match($tx->status) {
                'paid'      => 'Pembayaran berhasil',
                'pending'   => 'Menunggu pembayaran',
                'cancelled' => 'Transaksi dibatalkan',
                default     => $tx->status,
            };
            $items[] = [
                'title'  => $statusLabel,
                'detail' => ($tx->user?->name ?? 'User') . ' — ' .
                            ($tx->event?->title ?? 'Event') . ' — ' .
                            'Rp ' . number_format($tx->total, 0, ',', '.'),
                'time'   => $tx->created_at?->diffForHumans() ?? '-',
                'status' => $tx->status,
            ];
        }

        return $items;
    }

    public function render()
    {
        return view('livewire.admin.dashboard', [
            'title'              => 'Dashboard Admin',
            'stats'              => $this->stats,
            'timeline'           => $this->timeline,
            'recentTransactions' => $this->recentTransactions,
        ]);
    }
}
