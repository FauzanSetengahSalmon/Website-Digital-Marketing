<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// --- 1. GUEST / PUBLIC AREA ---
// Area yang bisa diakses tanpa login
Route::get('/', [ProductController::class, 'home'])->name('home');
Route::view('/tentang-kami', 'about')->name('about');
Route::get('/katalog', [ProductController::class, 'index'])->name('customer.katalog');

// --- 2. PINTU OTOMATIS (Dashboard Redirector) ---
// Mengarahkan user ke dashboard yang sesuai setelah login
Route::get('/dashboard', function () {
    $role = Auth::user()->role;
    if ($role === 'admin') return redirect()->route('admin.dashboard');
    if ($role === 'kwt') return redirect()->route('kwt.dashboard');

    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

// --- 3. AUTHENTICATED AREA (Hanya User Login) ---
Route::middleware('auth')->group(function () {

    // ==========================================
    // AREA ADMIN (Role: admin)
    // ==========================================
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
        Route::get('/users', [AdminController::class, 'manageUsers'])->name('users');
    });

    // ==========================================
    // AREA KWT (Role: kwt)
    // ==========================================
    Route::middleware(['role:kwt'])->prefix('kwt')->name('kwt.')->group(function () {
        Route::get('/dashboard', [OrderController::class, 'kwtDashboard'])->name('dashboard');

        // Produk CRUD
        Route::get('/list-produk', [ProductController::class, 'kwtProducts'])->name('products');
        Route::post('/tambah-produk', [ProductController::class, 'store'])->name('products.store');
        Route::get('/produk/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/produk/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/hapus-produk/{id}', [ProductController::class, 'destroy'])->name('products.destroy');

        // Pesanan & Laporan
        Route::get('/list-pesanan', [OrderController::class, 'kwtOrders'])->name('orders');
        Route::post('/pesanan-selesai/{id}', [OrderController::class, 'markAsDone'])->name('orders.done');
        Route::get('/laporan', [ProductController::class, 'laporanTransaksi'])->name('laporan');

        // Profil Khusus KWT
        Route::get('/profile', [ProfileController::class, 'editKwt'])->name('profile');
    });

    // ==========================================
    // AREA CUSTOMER / KERANJANG BELANJA
    // ==========================================
    Route::controller(CartController::class)->group(function () {
        // Tampil Halaman Keranjang
        Route::get('/cart', 'index')->name('cart.index'); 
        
        // Tambah Produk ke Keranjang (AJAX dari Katalog)
        Route::post('/cart/add/{id}', 'store')->name('cart.add'); 
        
        // Update Jumlah Barang (AJAX dari tombol +/- di Keranjang)
        Route::patch('/cart/update/{id}', 'update')->name('cart.update'); 
        
        // Hapus Barang dari Keranjang
        Route::delete('/cart/{id}', 'destroy')->name('cart.destroy'); 
    });

    // ==========================================
    // AREA UMUM CUSTOMER (Order & Profile)
    // ==========================================
    
    // Riwayat Pesanan
    Route::get('/riwayat-pesanan', [OrderController::class, 'history'])->name('orders.history');

    // Profil Customer Dasar
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Load Route bawaan Laravel (Login, Register, dll)
require __DIR__ . '/auth.php';