<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IkanController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\PengirimanController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

// Redirect root to dashboard or login
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
})->name('home');

Route::middleware('auth')->group(function () {
    // Shared executive & operational dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Shared read & oversight (Admin & Owner)
    Route::get('/stoks', [StokController::class, 'index'])->name('stoks.index');
    Route::get('/pemesanans', [PemesananController::class, 'index'])->name('pemesanans.index');
    Route::get('/pemesanans/{pemesanan}', [PemesananController::class, 'show'])->name('pemesanans.show');
    Route::get('/pengirimans', [PengirimanController::class, 'index'])->name('pengirimans.index');
    Route::get('/transaksis', [TransaksiController::class, 'index'])->name('transaksis.index');
    Route::get('/transaksis/{transaksi}', [TransaksiController::class, 'show'])->name('transaksis.show');
    
    // Reports & Analytics (Admin & Owner)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export/csv', [LaporanController::class, 'exportCsv'])->name('laporan.export.csv');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');

    // Admin exclusive operational actions
    Route::middleware('role:admin')->group(function () {
        // Fish Master Data
        Route::resource('ikans', IkanController::class);

        // Cold Storage Stock Adjustment
        Route::patch('/stoks/{stok}', [StokController::class, 'update'])->name('stoks.update');

        // B2B Wholesale Order Operational Workflow
        Route::get('/orders/create', [PemesananController::class, 'create'])->name('pemesanans.create');
        Route::post('/orders', [PemesananController::class, 'store'])->name('pemesanans.store');
        Route::post('/orders/{pemesanan}/confirm', [PemesananController::class, 'confirm'])->name('pemesanans.confirm');
        Route::patch('/orders/{pemesanan}/status', [PemesananController::class, 'updateStatus'])->name('pemesanans.status');
        Route::delete('/orders/{pemesanan}', [PemesananController::class, 'destroy'])->name('pemesanans.destroy');

        // Internal Fleet Dispatch & Tracking
        Route::patch('/pengirimans/{pengiriman}', [PengirimanController::class, 'update'])->name('pengirimans.update');
    });
});

require __DIR__.'/auth.php';

