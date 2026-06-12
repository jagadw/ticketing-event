<?php

namespace App\Livewire\Admin;

use App\Models\promos;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class PromoManagement extends Component
{
    public string $search = '';

    public array $form;

    public $promos = [];

    public bool $isEditMode = false;

    public ?int $editingId = null;

    public function mount(): void
    {
        $this->form = $this->defaultForm();
        $this->loadPromos();
    }

    public function loadPromos(): void
    {
        $this->promos = promos::all()->map(function ($promo) {
            return [
                'id' => $promo->id,
                'promo_code' => $promo->promo_code,
                'discount_percentage' => $promo->discount_percentage,
                'start_date' => $promo->start_date?->format('Y-m-d H:i'),
                'end_date' => $promo->end_date?->format('Y-m-d H:i'),
                'is_active' => $promo->is_active,
            ];
        })->toArray();
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
        $data = $this->validate()['form'];

        if ($this->isEditMode && $this->editingId) {
            $promo = promos::findOrFail($this->editingId);
            $promo->update([
                'promo_code' => $data['promo_code'],
                'discount_percentage' => $data['discount_percentage'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => $data['is_active'],
            ]);
            $this->isEditMode = false;
            $this->editingId = null;
        } else {
            promos::create([
                'promo_code' => $data['promo_code'],
                'discount_percentage' => $data['discount_percentage'],
                'start_date' => $data['start_date'],
                'end_date' => $data['end_date'],
                'is_active' => $data['is_active'] ?? true,
            ]);
        }

        $this->form = $this->defaultForm();
        $this->loadPromos();
    }

    public function editPromo(int $id): void
    {
        $promo = promos::findOrFail($id);
        $this->isEditMode = true;
        $this->editingId = $id;
        $this->form = [
            'promo_code' => $promo->promo_code,
            'discount_percentage' => $promo->discount_percentage,
            'start_date' => $promo->start_date?->format('Y-m-d\TH:i'),
            'end_date' => $promo->end_date?->format('Y-m-d\TH:i'),
            'is_active' => $promo->is_active,
        ];
    }

    public function cancelEdit(): void
    {
        $this->isEditMode = false;
        $this->editingId = null;
        $this->form = $this->defaultForm();
    }

    public function deletePromo(int $id): void
    {
        promos::destroy($id);
        $this->loadPromos();
    }

    public function toggleActive(int $id): void
    {
        $promo = promos::findOrFail($id);
        $promo->update(['is_active' => !$promo->is_active]);
        $this->loadPromos();
    }

    public function getFilteredPromosProperty(): array
    {
        $search = strtolower(trim($this->search));

        if ($search === '') {
            return $this->promos;
        }

        return array_values(array_filter($this->promos, function (array $promo) use ($search): bool {
            return str_contains(strtolower($promo['promo_code']), $search);
        }));
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
        return view('livewire.admin.promo-management', ['title' => 'Management Promo']);
    }
}
