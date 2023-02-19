<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\ReservationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::get('getToken', [ApiTokenController::class, 'getToken'])->middleware('auth.basic');

Route::middleware('jwt')->group(function () {
    Route::get('reservations/list', [ReservationController::class, 'list']);

    Route::resource('reservations', ReservationController::class)->except(['create', 'edit', 'show']);
});
