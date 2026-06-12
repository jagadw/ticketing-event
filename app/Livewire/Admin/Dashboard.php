<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class Dashboard extends Component
{
    public array $stats = [
        ['label' => 'Total Event Aktif', 'value' => 18, 'note' => '+3 minggu ini'],
        ['label' => 'Transaksi Pending', 'value' => 42, 'note' => 'Perlu follow up'],
        ['label' => 'Pengguna Terdaftar', 'value' => 1280, 'note' => '+86 bulan ini'],
        ['label' => 'Revenue Bulan Ini', 'value' => 'Rp 84,6 jt', 'note' => 'Dari 312 order'],
    ];

    public array $timeline = [
        ['title' => 'Promo FLASH10 aktif', 'detail' => 'Kode promo baru dipasang untuk event akhir pekan.', 'time' => '12 menit lalu'],
        ['title' => 'Pembayaran diverifikasi', 'detail' => '18 transaksi berstatus paid dan siap diproses.', 'time' => '1 jam lalu'],
        ['title' => 'Event baru ditambahkan', 'detail' => 'Pameran Tech Night 2026 dijadwalkan untuk Agustus.', 'time' => '3 jam lalu'],
    ];

    public function render()
    {
        return view('livewire.admin.dashboard', ['title' => 'Dashboard Admin']);
    }
}