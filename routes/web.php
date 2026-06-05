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
use App\Http\Controllers\GeneralController;
use App\Http\Controllers\KurirController;
use App\Http\Controllers\SettingController;

/*
|--------------------------------------------------------------------------
| PUBLIC ROUTES
|--------------------------------------------------------------------------
*/

Route::get('/', [ProductController::class, 'home'])
    ->middleware(\App\Http\Middleware\RedirectHomeBerdasarkanRole::class)
    ->name('home');

Route::view('/tentang-kami', 'about')->name('about');

Route::get('/katalog', [ProductController::class, 'index'])
    ->name('customer.katalog');

Route::get('/produk/{id}', [ProductController::class, 'show'])
    ->name('customer.products.show');

/*
|--------------------------------------------------------------------------
| MIDTRANS CALLBACK
|--------------------------------------------------------------------------
*/

Route::post('/midtrans/callback', [CheckoutController::class, 'callback'])
    ->name('midtrans.callback');

/*
|--------------------------------------------------------------------------
| GENERAL
|--------------------------------------------------------------------------
*/

Route::post('/bug-report', [GeneralController::class, 'sendBugReport'])
    ->name('bug.report');

/*
|--------------------------------------------------------------------------
| KURIR DELIVERY LINK
|--------------------------------------------------------------------------
*/

Route::get('/delivery/{id}/{token}', [KurirController::class, 'showUpload'])
    ->name('kurir.upload');

Route::post('/delivery/{id}/{token}', [KurirController::class, 'storeUpload'])
    ->name('kurir.store');

/*
|--------------------------------------------------------------------------
| GOOGLE AUTH
|--------------------------------------------------------------------------
*/

Route::get('/auth/google', [GoogleController::class, 'redirect'])
    ->name('google.login');

Route::get('/auth/google/callback', [GoogleController::class, 'callback'])
    ->name('google.callback');

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
})->middleware(['auth'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| AUTH AREA
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

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            Route::get('/dashboard', [AdminController::class, 'dashboard'])
                ->name('dashboard');

            Route::get('/profile', [ProfileController::class, 'editAdmin'])
                ->name('profile');

            Route::get('/users', [AdminController::class, 'usersIndex'])
                ->name('users');

            /*
            |--------------------------------------------------------------------------
            | KURIR MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get('/kurir', [AdminController::class, 'adminKurirIndex'])
                ->name('kurir.index');

            Route::post('/kurir/store', [AdminController::class, 'storeKurir'])
                ->name('kurir.store');

            Route::put('/kurir/update/{id}', [AdminController::class, 'updateKurir'])
                ->name('kurir.update');

            Route::delete('/kurir/delete/{id}', [AdminController::class, 'destroyKurir'])
                ->name('kurir.destroy');

            Route::post('/kwt/{id}/anggota', [AdminController::class, 'storeAnggota'])->name('kwt.anggota.store');
            Route::delete('/kwt/anggota/{id}', [AdminController::class, 'destroyAnggota'])->name('kwt.anggota.destroy');


            Route::post('/kurir/{id}/kendaraan', [AdminController::class, 'storeKendaraan'])->name('kurir.kendaraan.store');
            Route::delete('/kurir/kendaraan/{id}', [AdminController::class, 'destroyKendaraan'])->name('kurir.kendaraan.destroy');
            
            /*
            |--------------------------------------------------------------------------
            | PENCAIRAN KURIR
            |--------------------------------------------------------------------------
            */

            Route::get('/pencairan-kurir', [AdminController::class, 'riwayatPencairanKurir'])
                ->name('kurir.pencairan');

            Route::post('/pencairan-kurir/store', [AdminController::class, 'storePencairanKurir'])
                ->name('kurir.pencairan.store');

            Route::post('/kurir/{id}/cairkan', [AdminController::class, 'cairkanKurir'])
                ->name('kurir.cairkan');

            Route::post('/kwt/{id}/cairkan', [AdminController::class, 'cairkan'])->name('kwt.cairkan');

            /*
            |--------------------------------------------------------------------------
            | KWT MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get('/kwt', [AdminController::class, 'kwtIndex'])
                ->name('kwt');

            Route::post('/kwt/store', [AdminController::class, 'storeKwt'])
                ->name('kwt.store');

            Route::put('/kwt/update/{id}', [AdminController::class, 'updateKwt'])
                ->name('kwt.update');

            Route::delete('/kwt/delete/{id}', [AdminController::class, 'destroyKwt'])
                ->name('kwt.destroy');

            Route::patch('/kwt/verify/{id}', [AdminController::class, 'verifyKwt'])
                ->name('kwt.verify');
            /*
            |--------------------------------------------------------------------------
            | SALES & ORDER MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get('/sales', [AdminController::class, 'allSales'])
                ->name('sales.index');

            Route::put('/order/{id}/status', [AdminController::class, 'updateOrderStatus'])
                ->name('order.status');

            /*
            |--------------------------------------------------------------------------
            | REJECT & REFUND ORDER (ADMIN)
            |--------------------------------------------------------------------------
            */

            Route::post('/orders/{id}/reject', [ProductController::class, 'tolakPesanan'])
                ->name('orders.reject');

            Route::put('/order/{id}/proses-refund', [AdminController::class, 'prosesRefund'])
                ->name('orders.refund');

            /*
            |--------------------------------------------------------------------------
            | PRINT INVOICE
            |--------------------------------------------------------------------------
            */

            Route::get('/order/{id}/invoice-kwt', [AdminController::class, 'printInvoiceKwt'])
                ->name('order.invoice.kwt');

            Route::get('/order/{id}/invoice-kurir', [AdminController::class, 'printInvoiceKurir'])
                ->name('order.invoice.kurir');

            Route::get('/invoice-kwt-batch', [AdminController::class, 'printInvoiceKwtBatch'])
                ->name('invoice.kwt.batch');

            Route::get('/invoice-kurir-batch', [AdminController::class, 'printInvoiceKurirBatch'])
                ->name('invoice.kurir.batch');

            /*
            |--------------------------------------------------------------------------
            | REPORTS
            |--------------------------------------------------------------------------
            */

            Route::get('/kurir/{id}/laporan', [AdminController::class, 'reportKurir'])
                ->name('kurir.laporan');

            Route::get('/kwt/{id}/laporan', [AdminController::class, 'reportKwt'])
                ->name('kwt.laporan');

            Route::get('/admin/settings', [SettingController::class, 'index'])->name('settings.index');

            Route::put('/admin/settings/update', [SettingController::class, 'update'])->name('settings.update');
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

            /*
            |--------------------------------------------------------------------------
            | DASHBOARD
            |--------------------------------------------------------------------------
            */

            Route::get('/dashboard', [OrderController::class, 'kwtDashboard'])
                ->name('dashboard');

            /*
            |--------------------------------------------------------------------------
            | REPORT TANGGAPAN
            |--------------------------------------------------------------------------
            */

            Route::patch('/reports/{id}/tanggapan', [ReportController::class, 'updateTanggapan'])
                ->name('reports.update-tanggapan');

            /*
            |--------------------------------------------------------------------------
            | PRODUCT MANAGEMENT
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

            Route::delete('/produk/{id}', [ProductController::class, 'destroy'])
                ->name('products.destroy');

            /*
            |--------------------------------------------------------------------------
            | KWT ORDERS
            |--------------------------------------------------------------------------
            */

            Route::get('/list-pesanan', [OrderController::class, 'kwtOrders'])
                ->name('orders');

            Route::get('/proses-pesanan/{id}', [OrderController::class, 'kwtOrderProcess'])
                ->name('orders.process');

            Route::get('/detail-pesanan/{id}', [OrderController::class, 'kwtOrderDetail'])
                ->name('orders.detail');

            /*
            |--------------------------------------------------------------------------
            | ACCEPT ORDER
            |--------------------------------------------------------------------------
            */

            Route::put('/orders/{id}/accept', [OrderController::class, 'acceptOrder'])
                ->name('orders.accept');

            /*
            |--------------------------------------------------------------------------
            | KWT READY STOCK
            |--------------------------------------------------------------------------
            */

            Route::put('/orders/{id}/ready-stock', [OrderController::class, 'readyStock'])
                ->name('orders.readyStock');
            /*
            |--------------------------------------------------------------------------
            | REJECT / CANCEL ORDER
            |--------------------------------------------------------------------------
            */

            Route::post('/orders/{id}/reject', [OrderController::class, 'rejectOrder'])
                ->name('orders.reject');

            /*
            |--------------------------------------------------------------------------
            | UPLOAD BUKTI PENGIRIMAN
            |--------------------------------------------------------------------------
            */

            Route::post('/detail-pesanan/{id}/kirim', [OrderController::class, 'kirimPesanan'])
                ->name('orders.kirim');

            /*
            |--------------------------------------------------------------------------
            | KWT REPORT & FINANCE
            |--------------------------------------------------------------------------
            */

            Route::get('/laporan', [OrderController::class, 'kwtLaporan'])
                ->name('laporan');

            Route::delete('/laporan/reset', [OrderController::class, 'resetLaporan'])
                ->name('laporan.reset');

            Route::get('/export-excel', [OrderController::class, 'exportExcel'])
                ->name('export.excel');

            Route::post('/withdraw', [OrderController::class, 'withdrawPendapatan'])
                ->name('withdraw');

            /*
            |--------------------------------------------------------------------------
            | REPORT MANAGEMENT
            |--------------------------------------------------------------------------
            */

            Route::get('/reports', [ReportController::class, 'kwtIndex'])
                ->name('reports.index');

            Route::patch('/reports/{id}/status', [ReportController::class, 'updateStatus'])
                ->name('reports.update-status');

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

        Route::get('/cart', 'index')
            ->name('cart.index');

        Route::post('/cart/add/{id}', 'store')
            ->name('cart.add');

        Route::patch('/cart/update/{id}', 'update')
            ->name('cart.update');

        Route::delete('/cart/{id}', 'destroy')
            ->name('cart.destroy');
    });

    /*
    |--------------------------------------------------------------------------
    | CHECKOUT
    |--------------------------------------------------------------------------
    */

    Route::controller(CheckoutController::class)->group(function () {

        Route::get('/checkout', 'checkout')
            ->name('checkout.index');

        Route::post('/checkout/process', 'process')
            ->name('checkout.process');
    });

    /*
    |--------------------------------------------------------------------------
    | ORDER HISTORY & REFUND (CUSTOMER)
    |--------------------------------------------------------------------------
    */

    Route::controller(OrderController::class)->group(function () {

        Route::get('/riwayat-pesanan', 'history')
            ->name('orders.history');

        Route::get('/riwayat-pesanan/{id}', 'show')
            ->name('orders.history.detail');

        Route::patch('/riwayat-pesanan/{id}/complete', 'complete')
            ->name('orders.complete');

        Route::post('/riwayat-pesanan/{id}/refund', 'ajukanRefund')
            ->name('orders.refund');
    });

    /*
    |--------------------------------------------------------------------------
    | CUSTOMER REPORT
    |--------------------------------------------------------------------------
    */

    Route::post('/riwayat-pesanan/{id}/report', [ReportController::class, 'store'])
        ->name('orders.report.store');

    /*
    |--------------------------------------------------------------------------
    | GLOBAL PROFILE
    |--------------------------------------------------------------------------
    */

    Route::controller(ProfileController::class)->group(function () {

        Route::get('/profile', 'edit')
            ->name('profile.edit');

        Route::patch('/profile', 'update')
            ->name('profile.update');

        Route::post('/profile/photo', 'updatePhoto')
            ->name('profile.update.photo');

        Route::delete('/profile', 'destroy')
            ->name('profile.destroy');
    });
});

require __DIR__ . '/auth.php';
