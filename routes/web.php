<?php

use App\Http\Controllers\Auth\ExpenseController;
use App\Http\Controllers\Guest\LoginController;
use App\Http\Controllers\Guest\RegisterController;
use Illuminate\Support\Facades\Route;

Route::controller(LoginController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'index')->name('register');
});

Route::view('/dashboard', 'auth.dashboard.index')->name('dashboard.index');

Route::resource('expense', ExpenseController::class);
