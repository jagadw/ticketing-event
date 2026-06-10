<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class EventManagement extends Component
{
    public string $search = '';

    public array $form;

    public array $events = [];

    public function mount(): void
    {
        $this->form = $this->defaultForm();

        $this->events = [
            ['id' => 1, 'title' => 'Seminar Startup Growth', 'event_date' => '2026-06-18 19:00', 'location' => 'Jakarta Convention Center', 'ticket_price' => 250000, 'quota' => 250, 'status' => 'Published'],
            ['id' => 2, 'title' => 'Music Night Festival', 'event_date' => '2026-07-02 20:00', 'location' => 'Lapangan Banteng', 'ticket_price' => 175000, 'quota' => 800, 'status' => 'Draft'],
            ['id' => 3, 'title' => 'Workshop UI Minimal', 'event_date' => '2026-07-10 09:00', 'location' => 'Online Zoom', 'ticket_price' => 99000, 'quota' => 120, 'status' => 'Published'],
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
        ];
    }

    public function saveEvent(): void
    {
        $data = $this->validate()['form'];

        array_unshift($this->events, [
            'id' => count($this->events) + 1,
            'title' => $data['title'],
            'event_date' => $data['event_date'],
            'location' => $data['location'],
            'ticket_price' => (float) $data['ticket_price'],
            'quota' => (int) $data['quota'],
            'status' => 'Draft',
        ]);

        $this->form = $this->defaultForm();
    }

    public function deleteEvent(int $id): void
    {
        $this->events = array_values(array_filter(
            $this->events,
            fn (array $event): bool => $event['id'] !== $id,
        ));
    }

    public function getFilteredEventsProperty(): array
    {
        $search = strtolower(trim($this->search));

        if ($search === '') {
            return $this->events;
        }

        return array_values(array_filter($this->events, function (array $event) use ($search): bool {
            return str_contains(strtolower($event['title']), $search)
                || str_contains(strtolower($event['location']), $search)
                || str_contains(strtolower($event['status']), $search);
        }));
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
        ];
    }

    public function render()
    {
        return view('livewire.admin.event-management')
            ->layout('layouts.admin', ['title' => 'Management Event']);
    }
}