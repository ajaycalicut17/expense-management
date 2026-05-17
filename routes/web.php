<?php

use App\Http\Controllers\Auth\DashboardController;
use App\Http\Controllers\Auth\ExpenseController;
use App\Http\Controllers\Guest\LoginController;
use App\Http\Controllers\Guest\RegisterController;
use App\Models\Expense;
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

    Route::resource('expense', ExpenseController::class)
        ->middlewareFor(['index'], [
            'can:viewAny,'.Expense::class,
        ])
        ->middlewareFor(['create', 'store'], [
            'can:create,'.Expense::class,
        ])
        ->middlewareFor(['show'], [
            'can:view,expense',
        ])
        ->middlewareFor(['edit', 'update'], [
            'can:update,expense',
        ])
        ->middlewareFor(['destroy'], [
            'can:delete,expense',
        ]);

    Route::controller(DashboardController::class)->group(function () {

        Route::get('/dashboard', 'index')->name('dashboard.index');
        Route::get('/average-daily-expense', 'averageDailyExpense')->name('average-daily-expense');
    });
});
