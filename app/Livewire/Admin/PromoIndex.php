<?php

namespace App\Livewire\Admin;

use App\Models\promos;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PromoIndex extends Component
{
    public string $search = '';

    public function deletePromo(int $id): void
    {
        promos::destroy($id);
        $this->dispatch('notify', type: 'success', message: 'Promo berhasil dihapus');
    }

    public function toggleActive(int $id): void
    {
        $promo = promos::findOrFail($id);
        $promo->update(['is_active' => !$promo->is_active]);
        $this->dispatch('notify', type: 'success', message: 'Status promo berhasil diubah');
    }

    public function getFilteredPromosProperty(): array
    {
        $search = strtolower(trim($this->search));
        $promos = promos::all();

        if ($search === '') {
            return $promos->toArray();
        }

        return $promos->filter(function ($promo) use ($search) {
            return str_contains(strtolower($promo->promo_code), $search);
        })->toArray();
    }

    public function render()
    {
        return view('livewire.admin.promo-index', ['title' => 'Daftar Promo']);
    }
}
