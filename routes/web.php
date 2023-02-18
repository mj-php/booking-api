<?php

use App\Http\Controllers\CustomAuthController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/login', [CustomAuthController::class, 'index'])->name('login');
    Route::post('/custom-login', [CustomAuthController::class, 'customLogin'])->name('login.custom');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('welcome');
    })->name('welcome');

    Route::get('/signout', [CustomAuthController::class, 'signOut'])->name('signout');

    Route::get('reservations/list', [ReservationController::class, 'list'])->name('reservations.list');

    Route::resource('reservations', ReservationController::class)->except(['create', 'edit', 'show']);
});
