<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class TransactionIndex extends Component
{
    public string $search = '';

    public string $statusFilter = 'all';

    public array $transactions = [];

    public function mount(): void
    {
        $this->transactions = [
            ['id' => 'TRX-2026-0001', 'user' => 'Alya Putri', 'event' => 'Seminar Startup Growth', 'quantity' => 2, 'total' => 500000, 'status' => 'paid', 'method' => 'Midtrans', 'date' => '2026-06-10 11:24'],
            ['id' => 'TRX-2026-0002', 'user' => 'Rizky Ramadhan', 'event' => 'Workshop UI Minimal', 'quantity' => 1, 'total' => 99000, 'status' => 'pending', 'method' => 'Transfer', 'date' => '2026-06-10 12:18'],
            ['id' => 'TRX-2026-0003', 'user' => 'Nabila Sari', 'event' => 'Music Night Festival', 'quantity' => 4, 'total' => 700000, 'status' => 'cancelled', 'method' => 'E-Wallet', 'date' => '2026-06-09 20:41'],
        ];
    }

    public function getFilteredTransactionsProperty(): array
    {
        $search = strtolower(trim($this->search));

        return array_values(array_filter($this->transactions, function (array $transaction) use ($search): bool {
            $matchesSearch = $search === ''
                || str_contains(strtolower($transaction['id']), $search)
                || str_contains(strtolower($transaction['user']), $search)
                || str_contains(strtolower($transaction['event']), $search)
                || str_contains(strtolower($transaction['method']), $search);

            $matchesStatus = $this->statusFilter === 'all' || $transaction['status'] === $this->statusFilter;

            return $matchesSearch && $matchesStatus;
        }));
    }

    public function render()
    {
        return view('livewire.admin.transaction-index')
            ->layout('layouts.admin', ['title' => 'Daftar Transaksi']);
    }
}