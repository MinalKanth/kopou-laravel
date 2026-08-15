<?php

use App\Http\Controllers\CheckoutController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Checkout Routes — Phase 7
|--------------------------------------------------------------------------
| Checkout requires an account (guests are asked to log in / register at
| this point) so the order and its Razorpay payment can be tied to a user.
*/

Route::middleware('auth')->prefix('checkout')->name('checkout.')->group(function () {
    Route::get('/', [CheckoutController::class, 'show'])->name('show');
    Route::post('/', [CheckoutController::class, 'store'])->name('store');
    Route::post('/verify', [CheckoutController::class, 'verify'])->name('verify');
    Route::post('/{order}/cancel', [CheckoutController::class, 'cancel'])->name('cancel');
    Route::get('/success/{order}', [CheckoutController::class, 'success'])->name('success');
});
