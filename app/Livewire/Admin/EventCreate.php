<?php

namespace App\Livewire\Admin;

use App\Models\events;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class EventCreate extends Component
{
    use WithFileUploads;

    public array $form;

    public $image;

    public function mount(): void
    {
        $this->form = $this->defaultForm();
    }

    protected function rules(): array
    {
        return [
            'form.title' => ['required', 'string', 'min:3'],
            'form.description' => ['required', 'string', 'min:10'],
            'form.event_date' => ['required', 'date'],
            'form.location' => ['required', 'string', 'min:3'],
            'form.ticket_price' => ['required', 'numeric', 'min:0'],
            'form.quota' => ['required', 'integer', 'min:1'],
            'form.status' => ['required', 'in:Draft,Published'],
            'image' => ['nullable', 'image', 'max:2048'],
        ];
    }

    public function saveEvent(): void
    {
        try {
            $this->validate();

            $eventData = $this->form;

            if ($this->image) {
                $eventData['image'] = $this->image->store('events', 'public');
            }

            events::create($eventData);

            $this->redirect(route('admin.events.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal menambahkan event: ' . $e->getMessage());
        }
    }

    protected function defaultForm(): array
    {
        return [
            'title' => '',
            'description' => '',
            'event_date' => '',
            'location' => '',
            'ticket_price' => '',
            'quota' => '',
            'status' => 'Draft',
        ];
    }

    public function render()
    {
        return view('livewire.admin.event-create', ['title' => 'Tambah Event']);
    }
}
