<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'home'])->name('home');
Route::view('/tentang-kami', 'about')->name('about');
Route::get('/katalog', [ProductController::class, 'index'])->name('customer.katalog');

/*
|--------------------------------------------------------------------------
| GOOGLE AUTH
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

/*
|--------------------------------------------------------------------------
| DASHBOARD REDIRECT
|--------------------------------------------------------------------------
*/

Route::get('/dashboard', function () {

    $user = Auth::user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role === 'kwt') {
        return redirect()->route('kwt.dashboard');
    }

    return redirect()->route('home');

})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN AREA
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:admin'])
        ->prefix('admin')
        ->name('admin.')
        ->group(function () {

            // DASHBOARD
            Route::get('/dashboard', [AdminController::class, 'dashboard'])
                ->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | FIX ROUTE YANG ERROR DI DASHBOARD
            |--------------------------------------------------------------------------
            */

            // ✅ FIX: admin.users (INI YANG KAMU ERROR SEBELUMNYA)
            Route::get('/users', [AdminController::class, 'kwtIndex'])
                ->name('users');

            Route::post('/users/store', [AdminController::class, 'storeKwt'])
                ->name('users.store');

            // tambahan alias aman
            Route::get('/kwt', [AdminController::class, 'kwtIndex'])
                ->name('kwt');

            Route::post('/kwt/store', [AdminController::class, 'storeKwt'])
                ->name('kwt.store');
        });

    /*
    |--------------------------------------------------------------------------
    | KWT AREA
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:kwt'])
        ->prefix('kwt')
        ->name('kwt.')
        ->group(function () {

            Route::get('/dashboard', [OrderController::class, 'kwtDashboard'])
                ->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | PRODUCT ROUTES (FIX ERROR KAMU DI SINI)
            |--------------------------------------------------------------------------
            */

            Route::get('/list-produk', [ProductController::class, 'kwtProducts'])
                ->name('products');

            Route::post('/tambah-produk', [ProductController::class, 'store'])
                ->name('products.store');

            Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])
                ->name('products.edit');

            Route::put('/produk/{id}', [ProductController::class, 'update'])
                ->name('products.update');

            // ✅ FIX ERROR INI: kwt.products.destroy
            Route::delete('/produk/{id}', [ProductController::class, 'destroy'])
                ->name('products.destroy');

            /*
            |--------------------------------------------------------------------------
            | ORDER
            |--------------------------------------------------------------------------
            */

            Route::get('/list-pesanan', [OrderController::class, 'kwtOrders'])
                ->name('orders');

            Route::get('/detail-pesanan/{id}', [OrderController::class, 'kwtOrderDetail'])
                ->name('orders.detail');

            Route::post('/update-status/{id}', [OrderController::class, 'updateStatus'])
                ->name('order.status');

            /*
            |--------------------------------------------------------------------------
            | REPORT
            |--------------------------------------------------------------------------
            */

            Route::get('/laporan', [ProductController::class, 'laporanTransaksi'])
                ->name('laporan');

            /*
            |--------------------------------------------------------------------------
            | PROFILE
            |--------------------------------------------------------------------------
            */

            Route::get('/profile', [ProfileController::class, 'editKwt'])
                ->name('profile');
        });

    /*
    |--------------------------------------------------------------------------
    | CART
    |--------------------------------------------------------------------------
    */

    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/cart/add/{id}', 'store')->name('cart.add');
        Route::patch('/cart/update/{id}', 'update')->name('cart.update');
        Route::delete('/cart/{id}', 'destroy')->name('cart.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | ORDER CUSTOMER
    |--------------------------------------------------------------------------
    */

    Route::controller(OrderController::class)->group(function () {
        Route::get('/checkout', 'checkout')->name('checkout.index');
        Route::post('/checkout/process', 'process')->name('checkout.process');
        Route::get('/riwayat-pesanan', 'history')->name('orders.history');
        Route::get('/riwayat-pesanan/{id}', 'show')->name('orders.detail');
    });

    /*
    |--------------------------------------------------------------------------
    | PROFILE
    |--------------------------------------------------------------------------
    */

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';