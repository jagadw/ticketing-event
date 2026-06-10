<?php

namespace App\Livewire\Admin;

use Livewire\Component;

class UserManagement extends Component
{
    public string $search = '';

    public array $form;

    public array $users = [];

    public function mount(): void
    {
        $this->form = $this->defaultForm();

        $this->users = [
            ['id' => 1, 'name' => 'Admin Utama', 'email' => 'admin@ticket.local', 'role' => 'admin', 'status' => 'Active'],
            ['id' => 2, 'name' => 'Maya Putri', 'email' => 'maya@ticket.local', 'role' => 'user', 'status' => 'Active'],
            ['id' => 3, 'name' => 'Bima Saputra', 'email' => 'bima@ticket.local', 'role' => 'user', 'status' => 'Suspended'],
        ];
    }

    protected function rules(): array
    {
        return [
            'form.name' => ['required', 'string', 'min:3'],
            'form.email' => ['required', 'email'],
            'form.role' => ['required', 'in:admin,user'],
            'form.status' => ['required', 'in:Active,Suspended'],
        ];
    }

    public function saveUser(): void
    {
        $data = $this->validate()['form'];

        array_unshift($this->users, [
            'id' => count($this->users) + 1,
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'status' => $data['status'],
        ]);

        $this->form = $this->defaultForm();
    }

    public function deleteUser(int $id): void
    {
        $this->users = array_values(array_filter(
            $this->users,
            fn (array $user): bool => $user['id'] !== $id,
        ));
    }

    public function getFilteredUsersProperty(): array
    {
        $search = strtolower(trim($this->search));

        if ($search === '') {
            return $this->users;
        }

        return array_values(array_filter($this->users, function (array $user) use ($search): bool {
            return str_contains(strtolower($user['name']), $search)
                || str_contains(strtolower($user['email']), $search)
                || str_contains(strtolower($user['role']), $search)
                || str_contains(strtolower($user['status']), $search);
        }));
    }

    protected function defaultForm(): array
    {
        return [
            'name' => '',
            'email' => '',
            'role' => 'user',
            'status' => 'Active',
        ];
    }

    public function render()
    {
        return view('livewire.admin.user-management')
            ->layout('layouts.admin', ['title' => 'Management Pengguna']);
    }
}