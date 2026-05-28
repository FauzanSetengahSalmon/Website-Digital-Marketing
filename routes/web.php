<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\KwtController;

// --- PUBLIC ROUTES ---
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::view('/tentang-kami', 'about')->name('about');
Route::get('/katalog', [ProductController::class, 'index'])->name('customer.katalog');
Route::get('/produk/{id}', [ProductController::class, 'show'])->name('customer.products.show');

Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])->name('midtrans.callback');

// --- GOOGLE AUTH ---
Route::get('/auth/google', [GoogleController::class, 'redirect'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'callback'])->name('google.callback');

// --- DASHBOARD REDIRECT ---
Route::get('/dashboard', function () {
    $user = Auth::user();
    if ($user->role === 'admin') return redirect()->route('admin.dashboard');
    if ($user->role === 'kwt') return redirect()->route('kwt.dashboard');
    return redirect()->route('home');
})->middleware(['auth'])->name('dashboard');

// --- AUTH ROUTES ---
Route::middleware(['auth'])->group(function () {

    // --- ADMIN AREA ---
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [ProfileController::class, 'editAdmin'])->name('profile');
        Route::get('/users', [AdminController::class, 'usersIndex'])->name('users');

        // Kurir
        Route::get('/kurir', [AdminController::class, 'adminKurirIndex'])->name('kurir.index');
        Route::post('/kurir/store', [AdminController::class, 'storeKurir'])->name('kurir.store');
        Route::put('/kurir/update/{id}', [AdminController::class, 'updateKurir'])->name('kurir.update');
        Route::delete('/kurir/delete/{id}', [AdminController::class, 'destroyKurir'])->name('kurir.destroy');

        // Pencairan Kurir
        Route::get('/pencairan-kurir', [AdminController::class, 'riwayatPencairanKurir'])->name('kurir.pencairan');
        Route::post('/pencairan-kurir/store', [AdminController::class, 'storePencairanKurir'])->name('kurir.pencairan.store');

        // KWT & Penjualan
        Route::get('/kwt', [AdminController::class, 'kwtIndex'])->name('kwt');
        Route::post('/kwt/store', [AdminController::class, 'storeKwt'])->name('kwt.store');
        Route::put('/kwt/update/{id}', [AdminController::class, 'updateKwt'])->name('kwt.update');
        Route::delete('/kwt/delete/{id}', [AdminController::class, 'destroyKwt'])->name('kwt.destroy');
        Route::get('/kwt/{id}/laporan', [AdminController::class, 'reportKwt'])->name('kwt.laporan');
        Route::post('/kwt/{id}/cairkan', [AdminController::class, 'cairkan'])->name('kwt.cairkan');

        Route::get('/sales', [AdminController::class, 'allSales'])->name('sales.index');
        Route::put('/order/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('order.status');
        Route::get('/order/{id}/invoice-kwt', [AdminController::class, 'printInvoiceKwt'])->name('order.invoice.kwt');
        Route::get('/order/{id}/invoice-kurir', [AdminController::class, 'printInvoiceKurir'])->name('order.invoice.kurir');
        Route::get('/kurir/{id}/laporan', [AdminController::class, 'reportKurir'])->name('kurir.laporan');
    });

    // --- KWT AREA ---
    Route::middleware(['role:kwt'])->prefix('kwt')->name('kwt.')->group(function () {
        Route::get('/dashboard', [OrderController::class, 'kwtDashboard'])->name('dashboard');
        Route::get('/list-produk', [ProductController::class, 'kwtProducts'])->name('products');
        Route::post('/tambah-produk', [ProductController::class, 'store'])->name('products.store');
        Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/produk/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/produk/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        Route::get('/list-pesanan', [OrderController::class, 'kwtOrders'])->name('orders');
        Route::get('/proses-pesanan/{id}', [OrderController::class, 'kwtOrderProcess'])->name('orders.process');
        Route::get('/detail-pesanan/{id}', [OrderController::class, 'kwtOrderDetail'])->name('orders.detail');
        Route::post('/detail-pesanan/{id}/kirim', [OrderController::class, 'kirimPesanan'])->name('orders.kirim');

        Route::get('/laporan', [OrderController::class, 'kwtLaporan'])->name('laporan');
        Route::get('/profile', [ProfileController::class, 'editKwt'])->name('profile');
    });

    // --- CART ---
    Route::controller(CartController::class)->group(function () {
        Route::get('/cart', 'index')->name('cart.index');
        Route::post('/cart/add/{id}', 'store')->name('cart.add');
        Route::patch('/cart/update/{id}', 'update')->name('cart.update');
        Route::delete('/cart/{id}', 'destroy')->name('cart.destroy');
    });

    // --- CHECKOUT AREA ---
    Route::controller(CheckoutController::class)->group(function () {
        Route::get('/checkout', 'checkout')->name('checkout.index');
        Route::post('/checkout/process', 'process')->name('checkout.process');
    });

    // --- ORDERS HISTORY & CUSTOMER REPORT ---
    Route::controller(OrderController::class)->group(function () {
        Route::get('/riwayat-pesanan', 'history')->name('orders.history');
        Route::get('/riwayat-pesanan/{id}', 'show')->name('orders.history.detail');
        Route::patch('/riwayat-pesanan/{id}/complete', 'complete')->name('orders.complete');
    });

    // --- PROFILE GLOBAL ---
    Route::controller(ProfileController::class)->group(function () {
        Route::get('/profile', 'edit')->name('profile.edit');
        Route::patch('/profile', 'update')->name('profile.update');
        Route::post('/profile/photo', 'updatePhoto')->name('profile.update.photo');
        Route::delete('/profile', 'destroy')->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';