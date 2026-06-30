<?php

namespace App\Livewire\Admin;

use App\Models\transactions;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class TransactionIndex extends Component
{
    public string $search = '';

    public string $statusFilter = 'all';

    public array $transactions = [];

    public function mount(): void
    {
        $this->loadTransactions();
    }

    public function loadTransactions(): void
    {
        $this->transactions = transactions::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'id' => $transaction->id,
                    'user' => $transaction->user->name,
                    'event' => $transaction->event->title,
                    'method' => $transaction->payment_method,
                    'quantity' => $transaction->quantity,
                    'status' => $transaction->status,
                    'total' => $transaction->total,
                    'date' => $transaction->created_at?->format('d M Y H:i'),
                ];
            })
            ->toArray();
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
        return view('livewire.admin.transaction-index', ['title' => 'Daftar Transaksi']);
    }
}