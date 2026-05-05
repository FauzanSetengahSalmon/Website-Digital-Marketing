<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. GUEST / PUBLIC AREA ---
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::view('/tentang-kami', 'about')->name('about');
Route::get('/katalog', [ProductController::class, 'index'])->name('customer.katalog');

// --- 2. PINTU OTOMATIS (Dashboard Redirector) ---
Route::get('/dashboard', function () {
    $role = Auth::user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'kwt') return redirect()->route('kwt.dashboard');

    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// --- 3. AUTHENTICATED AREA ---
Route::middleware('auth')->group(function () {

    // ==========================================
    // AREA ADMIN (Role: admin)
    // ==========================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');
    });

    // ==========================================
    // AREA KWT (Role: kwt) - Jalur Eksklusif
    // ==========================================
    Route::middleware(['role:kwt'])->prefix('kwt')->name('kwt.')->group(function () {
        Route::get('/dashboard', [OrderController::class, 'kwtDashboard'])->name('dashboard');

        // Produk CRUD
        Route::get('/list-produk', [ProductController::class, 'kwtProducts'])->name('products');
        Route::post('/tambah-produk', [ProductController::class, 'store'])->name('products.store');

        // --- ROUTE EDIT & UPDATE ---
        Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/produk/{id}', [ProductController::class, 'update'])->name('products.update');

        Route::delete('/hapus-produk/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Pesanan & Laporan
        Route::get('/list-pesanan', [OrderController::class, 'kwtOrders'])->name('orders');
        Route::post('/pesanan-selesai/{id}', [OrderController::class, 'markAsDone'])->name('orders.done');
        
        // --- INI ROUTE LAPORAN YANG BARU ---
        Route::get('/laporan', [ProductController::class, 'laporanTransaksi'])->name('laporan');

        // Profil Khusus KWT
        Route::get('/profile', [ProfileController::class, 'editKwt'])->name('profile');
    });

    // ==========================================
    // AREA UMUM / CUSTOMER
    // ==========================================
    Route::get('/riwayat-pesanan', [OrderController::class, 'history'])->name('orders.history');

    // Profil Customer (Default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'store'])->name('cart.add');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
});

require __DIR__ . '/auth.php';