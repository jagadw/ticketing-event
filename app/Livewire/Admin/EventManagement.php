<?php

namespace App\Livewire\Admin;

use App\Models\events;
use Livewire\Component;
use Livewire\WithFileUploads;

class EventManagement extends Component
{
    use WithFileUploads;

    public string $search = '';

    public array $form;

    public $image;

    public bool $isEditMode = false;

    public ?int $editingId = null;

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
        $this->validate();

        $eventData = $this->form;

        if ($this->image) {
            $eventData['image'] = $this->image->store('events', 'public');
        }

        if ($this->isEditMode && $this->editingId) {
            $event = events::findOrFail($this->editingId);
            $event->update($eventData);
            $this->isEditMode = false;
            $this->editingId = null;
        } else {
            events::create($eventData);
        }

        $this->form = $this->defaultForm();
        $this->image = null;
    }

    public function editEvent(int $id): void
    {
        $event = events::findOrFail($id);
        $this->isEditMode = true;
        $this->editingId = $id;
        $this->form = [
            'title' => $event->title,
            'description' => $event->description,
            'event_date' => $event->event_date?->format('Y-m-d\TH:i'),
            'location' => $event->location,
            'ticket_price' => $event->ticket_price,
            'quota' => $event->quota,
            'status' => $event->status,
        ];
    }

    public function cancelEdit(): void
    {
        $this->isEditMode = false;
        $this->editingId = null;
        $this->form = $this->defaultForm();
        $this->image = null;
    }

    public function deleteEvent(int $id): void
    {
        events::destroy($id);
    }

    public function toggleStatus(int $id): void
    {
        $event = events::findOrFail($id);
        $newStatus = $event->status === 'Published' ? 'Draft' : 'Published';
        $event->update(['status' => $newStatus]);
    }

    public function getFilteredEventsProperty(): array
    {
        $search = strtolower(trim($this->search));
        $events = events::all();

        if ($search === '') {
            return $events->toArray();
        }

        return $events->filter(function ($event) use ($search) {
            return str_contains(strtolower($event->title), $search)
                || str_contains(strtolower($event->location), $search)
                || str_contains(strtolower($event->status), $search);
        })->toArray();
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
        return view('livewire.admin.event-management')
            ->layout('layouts.admin', ['title' => 'Management Event']);
    }
}