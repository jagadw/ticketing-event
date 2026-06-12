<?php

namespace App\Livewire\Admin;

use App\Models\promos;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PromoCreate extends Component
{
    public array $form;

    public function mount(): void
    {
        $this->form = $this->defaultForm();
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

    public function savePromo(): void
    {
        try {
            $this->validate();

            promos::create($this->form);

            $this->redirect(route('admin.promos.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menambahkan promo: ' . $e->getMessage());
        }
    }

    protected function defaultForm(): array
    {
        return [
            'promo_code' => '',
            'discount_percentage' => '',
            'start_date' => '',
            'end_date' => '',
            'is_active' => true,
        ];
    }

    public function render()
    {
        return view('livewire.admin.promo-create', ['title' => 'Tambah Promo']);
    }
}
