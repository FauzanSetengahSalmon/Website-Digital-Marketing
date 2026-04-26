<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\OrderController; // Import Controller baru
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::view('/tentang-kami', 'about')->name('about');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Route Profile (Bawaan Laravel Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/riwayat-pesanan', [OrderController::class, 'history'])->name('orders.history');
    
    Route::get('/riwayat-pesanan/{id}', [OrderController::class, 'show'])->name('orders.show');
});

require __DIR__ . '/auth.php';