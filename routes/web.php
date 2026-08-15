<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes — Phase 1 (home) + Phase 3 (product system)
|--------------------------------------------------------------------------
| Cart, checkout, auth, and admin routes are introduced in their own
| phases (6, 7, 5, 9) so we don't ship dead links ahead of the backend.
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories/{slug}', [ProductController::class, 'category'])->name('categories.show');
Route::get('/search', [ProductController::class, 'index'])->name('search');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/{slug}/toggle', [WishlistController::class, 'toggle'])
    ->name('wishlist.toggle')
    ->middleware('throttle:30,1'); // basic abuse guard on a write route

/*
|--------------------------------------------------------------------------
| Phase 5 — Authentication + Account routes
|--------------------------------------------------------------------------
| Kept in their own files (routes/auth.php, routes/account.php) rather
| than inlined here, so this file doesn't turn into a 300-line dumping
| ground as more phases add routes.
*/
require __DIR__.'/auth.php';
require __DIR__.'/account.php';

/*
|--------------------------------------------------------------------------
| Phase 6/7/9 — Cart, Checkout, Admin
|--------------------------------------------------------------------------
*/
require __DIR__.'/cart.php';
require __DIR__.'/checkout.php';
require __DIR__.'/admin.php';
