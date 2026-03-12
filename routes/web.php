<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'finances-app')->name('home');
Route::view('/demo', 'demo.demo')->name('demo');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('dashboard', function () {
        dd(request()->user());
    })->name('dashboard');
});

require __DIR__.'/settings.php';
