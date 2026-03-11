<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'finances-app')->name('home');
Route::view('/demo', 'demo.demo')->name('demo');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
