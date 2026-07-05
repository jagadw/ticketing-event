<?php

namespace App\Livewire\Admin;

use App\Models\events;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class EventIndex extends Component
{
    public string $search = '';

    public function deleteEvent(int $id): void
    {
        events::destroy($id);
        $this->dispatch('notify', type: 'success', message: 'Event berhasil dihapus');
    }

    public function toggleStatus(int $id): void
    {
        $event = events::findOrFail($id);
        $newStatus = $event->status === 'Published' ? 'Draft' : 'Published';
        $event->update(['status' => $newStatus]);
        $this->dispatch('notify', type: 'success', message: 'Status event berhasil diubah');
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

    public function render()
    {
        return view('livewire.admin.event-index', ['title' => 'Daftar Event']);
    }
}
