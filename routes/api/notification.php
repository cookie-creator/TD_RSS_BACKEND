<?php

use App\Http\Controllers\Notifications\UserNotificationController;

Route::get('/', [UserNotificationController::class, 'show'])->name('show');
Route::get('recent', [UserNotificationController::class, 'recent'])->name('recent');
Route::post('read', [UserNotificationController::class, 'read'])->name('read');
Route::post('unread', [UserNotificationController::class, 'unread'])->name('unread');
Route::post('read-all', [UserNotificationController::class, 'readAll'])->name('read-all');
