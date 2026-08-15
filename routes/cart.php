<?php

use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Cart Routes — Phase 6
|--------------------------------------------------------------------------
| Works for guests (session-backed cart) and logged-in users (DB-backed
| cart tied to their account). No 'auth' middleware here on purpose.
*/

Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/items', [CartController::class, 'store'])->name('items.store');
    Route::patch('/items/{item}', [CartController::class, 'update'])->name('items.update');
    Route::delete('/items/{item}', [CartController::class, 'destroy'])->name('items.destroy');
});
