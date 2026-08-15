<?php

use App\Http\Controllers\Account\AddressController;
use App\Http\Controllers\Account\DashboardController;
use App\Http\Controllers\Account\OrderController;
use App\Http\Controllers\Account\ProfileController;
use App\Http\Controllers\Account\SecurityController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Account Routes — Phase 5
|--------------------------------------------------------------------------
| Orders and Addresses are now wired up (Phase 6/7). Coupons/Reviews are
| still placeholders — those land with a future review/promo system.
*/

Route::middleware('auth')->prefix('account')->name('account.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/security', [SecurityController::class, 'edit'])->name('security.edit');
    Route::put('/security/password', [SecurityController::class, 'updatePassword'])
        ->middleware('throttle:5,1')
        ->name('security.password');
    Route::delete('/security', [SecurityController::class, 'destroy'])
        ->middleware('throttle:3,1')
        ->name('security.destroy');

    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::get('/addresses', [AddressController::class, 'index'])->name('addresses.index');
    Route::post('/addresses', [AddressController::class, 'store'])->name('addresses.store');
    Route::put('/addresses/{address}', [AddressController::class, 'update'])->name('addresses.update');
    Route::delete('/addresses/{address}', [AddressController::class, 'destroy'])->name('addresses.destroy');
});
