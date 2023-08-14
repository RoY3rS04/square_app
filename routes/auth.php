<?php

use App\Http\Controllers\LoggedUserController;
use App\Http\Controllers\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');

    Route::post('register', [RegisteredUserController::class, 'store']);

    Route::get('login', [LoggedUserController::class, 'create'])->name('login');

    Route::post('login', [LoggedUserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [LoggedUserController::class, 'destroy'])->name('logout');
});
