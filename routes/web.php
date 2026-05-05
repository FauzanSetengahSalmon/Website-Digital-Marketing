<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public Routes
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::view('/tentang-kami', 'about')->name('about');
Route::get('/katalog', [ProductController::class, 'index'])->name('customer.katalog');

// Dashboard Redirector
Route::get('/dashboard', function () {
    $role = Auth::user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'kwt') return redirect()->route('kwt.dashboard');
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // AREA ADMIN
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');
    });

    // AREA KWT (Penjual)
    Route::middleware(['role:kwt'])->prefix('kwt')->name('kwt.')->group(function () {
        Route::get('/dashboard', [OrderController::class, 'kwtDashboard'])->name('dashboard');
        
        // Produk CRUD
        Route::get('/list-produk', [ProductController::class, 'kwtProducts'])->name('products');
        Route::post('/tambah-produk', [ProductController::class, 'store'])->name('products.store');
        Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/produk/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/hapus-produk/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Pesanan Masuk
        Route::get('/list-pesanan', [OrderController::class, 'kwtOrders'])->name('orders');
        
        // --- INI PERBAIKANNYA: Nama disesuaikan dengan view ---
        Route::post('/update-status/{id}', [OrderController::class, 'updateStatus'])->name('order.status');
        
        Route::get('/laporan', [ProductController::class, 'laporanTransaksi'])->name('laporan');
        Route::get('/profile', [ProfileController::class, 'editKwt'])->name('profile');
    });

    // AREA KERANJANG
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index'); 
        Route::post('/cart/add/{id}', 'store')->name('cart.add'); 
        Route::patch('/cart/update/{id}', 'update')->name('cart.update'); 
        Route::delete('/cart/{id}', 'destroy')->name('cart.destroy'); 
    });

    // AREA CUSTOMER
    Route::controller(OrderController::class)->group(function () {
        Route::get('/checkout', 'checkout')->name('checkout.index');
        Route::post('/checkout/process', 'process')->name('checkout.process');
        Route::get('/riwayat-pesanan', 'history')->name('orders.history');
    });

    // AREA PROFILE
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';