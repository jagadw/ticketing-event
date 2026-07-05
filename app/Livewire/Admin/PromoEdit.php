<?php

namespace App\Livewire\Admin;

use App\Models\promos;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PromoEdit extends Component
{
    public promos $promo;

    public array $form;

    public function mount(): void
    {
        $this->form = [
            'promo_code' => $this->promo->promo_code,
            'discount_percentage' => $this->promo->discount_percentage,
            'start_date' => $this->promo->start_date?->format('Y-m-d\TH:i'),
            'end_date' => $this->promo->end_date?->format('Y-m-d\TH:i'),
            'is_active' => $this->promo->is_active,
        ];
    }

    protected function rules(): array
    {
        return [
            'form.promo_code' => ['required', 'string', 'min:2', 'max:50'],
            'form.discount_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'form.start_date' => ['required', 'date'],
            'form.end_date' => ['required', 'date', 'after_or_equal:form.start_date'],
            'form.is_active' => ['boolean'],
        ];
    }

    public function updatePromo(): void
    {
        try {
            $this->validate();

            $this->promo->update($this->form);

            $this->redirect(route('admin.promos.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui promo: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.promo-edit', ['title' => 'Edit Promo']);
    }
}
