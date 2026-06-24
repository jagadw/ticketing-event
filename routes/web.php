<?php

use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\EventCreate;
use App\Livewire\Admin\EventEdit;
use App\Livewire\Admin\EventIndex;
use App\Livewire\Admin\PromoCreate;
use App\Livewire\Admin\PromoEdit;
use App\Livewire\Admin\PromoIndex;
use App\Livewire\Admin\TransactionIndex;
use App\Livewire\Admin\UserManagement;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Middleware\EnsureAdmin;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/admin');

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::middleware(EnsureAdmin::class)->group(function () {
        Route::get('/', Dashboard::class)->name('dashboard');
        Route::get('/events', EventIndex::class)->name('events.index');
        Route::get('/events/create', EventCreate::class)->name('events.create');
        Route::get('/events/{event}/edit', EventEdit::class)->name('events.edit');
        Route::get('/promos', PromoIndex::class)->name('promos.index');
        Route::get('/promos/create', PromoCreate::class)->name('promos.create');
        Route::get('/promos/{promo}/edit', PromoEdit::class)->name('promos.edit');
        Route::get('/transactions', TransactionIndex::class)->name('transactions');
        Route::get('/users', UserManagement::class)->name('users');
    });
});

