<?php

namespace App\Livewire\Admin;

use App\Models\events;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.admin')]
class EventEdit extends Component
{
    use WithFileUploads;

    public events $event;

    public array $form;

    public $image;

    public function mount(): void
    {
        $this->form = [
            'title' => $this->event->title,
            'description' => $this->event->description,
            'event_date' => $this->event->event_date?->format('Y-m-d\TH:i'),
            'location' => $this->event->location,
            'ticket_price' => $this->event->ticket_price,
            'quota' => $this->event->quota,
            'status' => $this->event->status,
        ];
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

    public function updateEvent(): void
    {
        try {
            $this->validate();

            $eventData = $this->form;

            if ($this->image) {
                $eventData['image'] = $this->image->store('events', 'public');
            }

            $this->event->update($eventData);

            $this->redirect(route('admin.events.index'), navigate: true);
        } catch (\Exception $e) {
            session()->flash('error', 'Gagal memperbarui event: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.event-edit', ['title' => 'Edit Event']);
    }
}
