<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', static function () {
    // For testing propose
    return [
        'message' => __('Created by :name', ['name' => 'Cookie']),
    ];
});


Route::get('/test', [\App\Http\Controllers\TestController::class, 'index'])->name('test.inde');
