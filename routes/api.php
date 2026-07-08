<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\TransactionController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login',    [AuthController::class, 'login']);

Route::get('/events',         [EventController::class, 'index']);
Route::get('/events/{event}', [EventController::class, 'show']);

Route::post('/transactions/webhook', [TransactionController::class, 'webhook']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout',  [AuthController::class, 'logout']);
    Route::get('/auth/profile',  [AuthController::class, 'profile']);

    Route::get('/transactions',                             [TransactionController::class, 'history']);
    Route::post('/transactions/checkout',                   [TransactionController::class, 'checkout']);
    Route::get('/transactions/{transaction}',               [TransactionController::class, 'show']);
    Route::post('/transactions/{transaction}/pay',          [TransactionController::class, 'pay']);
    Route::get('/transactions/{transaction}/check-status',  [TransactionController::class, 'checkStatus']);
});
