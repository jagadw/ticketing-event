<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.admin')]
class UserManagement extends Component
{
    public string $search = '';

    public function toggleStatus(int $id): void
    {
        $user = User::findOrFail($id);
        $user->update(['status' => $user->status === 'Active' ? 'Suspended' : 'Active']);
    }

    public function getFilteredUsersProperty(): array
    {
        $search = strtolower(trim($this->search));
        $users = User::all();

        if ($search === '') {
            return $users->toArray();
        }

        return $users->filter(function ($user) use ($search) {
            return str_contains(strtolower($user->name), $search)
                || str_contains(strtolower($user->email), $search)
                || str_contains(strtolower($user->status ?? 'Active'), $search);
        })->toArray();
    }

    public function render()
    {
        return view('livewire.admin.user-management', ['title' => 'Management Pengguna']);
    }
}