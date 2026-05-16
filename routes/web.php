<?php

use App\Http\Controllers\Auth\ExpenseController;
use App\Http\Controllers\Guest\LoginController;
use App\Http\Controllers\Guest\RegisterController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {

    Route::controller(LoginController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/login', 'login')->name('login');
    });

    Route::controller(RegisterController::class)->group(function () {
        Route::get('/register', 'index')->name('register.index');
        Route::post('/register', 'store')->name('register.store');
    });
});

Route::middleware('auth')->group(function () {

    Route::controller(LoginController::class)->group(function () {
        Route::post('/logout', 'logout')->name('logout');
    });

    Route::view('/dashboard', 'auth.dashboard.index')->name('dashboard.index');

    Route::resource('expense', ExpenseController::class);
});
