<?php

use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'finances-app')->name('home');
Route::view('/demo', 'demo.demo')->name('demo');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::view('/index', 'web.index')->name('web.index');
    Route::get('/state', [\App\Http\Controllers\Web\StateController::class, 'getState']);

    Route::post('/transactions', [\App\Http\Controllers\Web\StateController::class, 'storeTransaction']);
    Route::put('/transactions/{transaction}', [\App\Http\Controllers\Web\StateController::class, 'updateTransaction']);
    Route::delete('/transactions/{transaction}', [\App\Http\Controllers\Web\StateController::class, 'deleteTransaction']);

    Route::post('/invoices', [\App\Http\Controllers\Web\StateController::class, 'storeInvoice']);
    Route::put('/invoices/{invoice}', [\App\Http\Controllers\Web\StateController::class, 'updateInvoice']);
    Route::delete('/invoices/{invoice}', [\App\Http\Controllers\Web\StateController::class, 'deleteInvoice']);

    Route::post('/investments', [\App\Http\Controllers\Web\StateController::class, 'storeInvestment']);
    Route::put('/investments/{investment}', [\App\Http\Controllers\Web\StateController::class, 'updateInvestment']);
    Route::delete('/investments/{investment}', [\App\Http\Controllers\Web\StateController::class, 'deleteInvestment']);

    Route::post('/goals', [\App\Http\Controllers\Web\StateController::class, 'storeGoal']);
    Route::put('/goals/{goal}', [\App\Http\Controllers\Web\StateController::class, 'updateGoal']);
    Route::delete('/goals/{goal}', [\App\Http\Controllers\Web\StateController::class, 'deleteGoal']);

    Route::post('/accounts', [\App\Http\Controllers\Web\StateController::class, 'storeAccount']);
    Route::put('/accounts/{account}', [\App\Http\Controllers\Web\StateController::class, 'updateAccount']);
    Route::delete('/accounts/{account}', [\App\Http\Controllers\Web\StateController::class, 'deleteAccount']);

    Route::post('/categories', [\App\Http\Controllers\Web\StateController::class, 'storeCategory']);
    Route::put('/categories/{category}', [\App\Http\Controllers\Web\StateController::class, 'updateCategory']);
    Route::delete('/categories/{category}', [\App\Http\Controllers\Web\StateController::class, 'deleteCategory']);
});

require __DIR__.'/settings.php';
