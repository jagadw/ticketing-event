<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\EventCreate;
use App\Livewire\Admin\EventEdit;
use App\Livewire\Admin\EventIndex;
use App\Livewire\Admin\PromoManagement;
use App\Livewire\Admin\TransactionIndex;
use App\Livewire\Admin\UserManagement;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/events', EventIndex::class)->name('events.index');
    Route::get('/events/create', EventCreate::class)->name('events.create');
    Route::get('/events/{event}/edit', EventEdit::class)->name('events.edit');
    Route::get('/promos', PromoManagement::class)->name('promos');
    Route::get('/transactions', TransactionIndex::class)->name('transactions');
    Route::get('/users', UserManagement::class)->name('users');
});
