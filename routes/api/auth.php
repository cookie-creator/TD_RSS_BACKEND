<?php

use App\Http\Controllers\Auth\AuthenticationController;

Route::post('login', [AuthenticationController::class, 'login'])->name('login');
Route::post('register', [AuthenticationController::class, 'register'])->name('register');

Route::post('reset-password', [AuthenticationController::class, 'reset'])->name('reset');
Route::post('new-password', [AuthenticationController::class, 'new'])->name('new');

Route::middleware('auth:sanctum')->get('me', [AuthenticationController::class, 'me'])->name('me');
