<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\EventManagement;
use App\Livewire\Admin\TransactionIndex;
use App\Livewire\Admin\UserManagement;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/events', EventManagement::class)->name('events');
    Route::get('/transactions', TransactionIndex::class)->name('transactions');
    Route::get('/users', UserManagement::class)->name('users');
});
