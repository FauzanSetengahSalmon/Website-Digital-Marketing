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
    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    if ($user->role === 'kwt') return redirect()->route('kwt.dashboard');
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH ROUTES (GROUP)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    /* --- ADMIN AREA --- */
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Manajemen User (Customer)
        Route::get('/users', [AdminController::class, 'usersIndex'])->name('users');
        
        // Manajemen Akun KWT
        Route::get('/kwt', [AdminController::class, 'kwtIndex'])->name('kwt');
        Route::post('/kwt/store', [AdminController::class, 'storeKwt'])->name('kwt.store');
        
        // Penjualan Global
        Route::get('/sales', [AdminController::class, 'allSales'])->name('sales');
    });

    /* --- KWT AREA --- */
    Route::middleware(['role:kwt'])->prefix('kwt')->name('kwt.')->group(function () {
        Route::get('/dashboard', [OrderController::class, 'kwtDashboard'])->name('dashboard');
        
        // Produk KWT
        Route::get('/list-produk', [ProductController::class, 'kwtProducts'])->name('products');
        Route::post('/tambah-produk', [ProductController::class, 'store'])->name('products.store');
        Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/produk/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        
        // Pesanan Masuk
        Route::get('/list-pesanan', [OrderController::class, 'kwtOrders'])->name('orders');
        Route::get('/proses-pesanan/{id}', [OrderController::class, 'kwtOrderProcess'])->name('orders.process');
        Route::get('/detail-pesanan/{id}', [OrderController::class, 'kwtOrderDetail'])->name('orders.detail');
        Route::post('/update-status/{id}', [OrderController::class, 'updateStatus'])->name('order.status');
        
        // Laporan & Profile
        Route::get('/laporan', [OrderController::class, 'kwtLaporan'])->name('laporan');
        Route::delete('/laporan/reset', [OrderController::class, 'resetLaporan'])->name('laporan.reset');
        Route::get('/profile', [ProfileController::class, 'editKwt'])->name('profile');
    });

    /* --- CART & CHECKOUT --- */
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/cart/add/{id}', 'store')->name('cart.add');
        Route::patch('/cart/update/{id}', 'update')->name('cart.update');
        Route::delete('/cart/{id}', 'destroy')->name('cart.destroy');
    });

    Route::controller(OrderController::class)->group(function () {
        Route::get('/checkout', 'checkout')->name('checkout.index');
        Route::post('/checkout/process', 'process')->name('checkout.process');
        Route::get('/riwayat-pesanan', 'history')->name('orders.history');
        Route::get('/riwayat-pesanan/{id}', 'show')->name('orders.detail');
    });

    /* --- PROFILE GLOBAL --- */
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::post('/profile/photo', 'updatePhoto')->name('profile.update.photo');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';