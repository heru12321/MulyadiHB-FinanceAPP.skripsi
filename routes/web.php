<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SuplierController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\JurnalController;
use App\Http\Controllers\LaporanController;

Route::get('/', function () { return redirect()->route('login'); });

// Auth routes
require __DIR__ . '/auth.php';

// Protected routes
Route::middleware('auth.erp')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Stok
    Route::resource('stok', StokController::class);

    // Suplier
    Route::resource('suplier', SuplierController::class);

    // Pelanggan
    Route::resource('pelanggan', PelangganController::class);

    // Penjualan
    Route::resource('transaksi', TransaksiController::class);

    // Pembelian
    Route::resource('pembelian', PembelianController::class);

    // Hutang & Piutang Pembayaran
    Route::prefix('pembayaran')->name('pembayaran.')->group(function () {
        Route::get('/',                          [PembayaranController::class, 'index'])->name('index');
        Route::get('/hutang/{id}',               [PembayaranController::class, 'formHutang'])->name('hutang.form');
        Route::post('/hutang/{id}',              [PembayaranController::class, 'bayarHutang'])->name('hutang.post');
        Route::get('/piutang/{id}',              [PembayaranController::class, 'formPiutang'])->name('piutang.form');
        Route::post('/piutang/{id}',             [PembayaranController::class, 'bayarPiutang'])->name('piutang.post');
    });

    // Jurnal Umum
    Route::get('/jurnal',       [JurnalController::class, 'index'])->name('jurnal.index');
    Route::get('/jurnal/{id}',  [JurnalController::class, 'show'])->name('jurnal.show');

    // Laporan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/buku-besar',  [LaporanController::class, 'bukuBesar'])->name('buku-besar');
        Route::get('/laba-rugi',   [LaporanController::class, 'labaRugi'])->name('laba-rugi');
        Route::get('/neraca',      [LaporanController::class, 'neraca'])->name('neraca');

        Route::get('/buku-besar/pdf',  [LaporanController::class, 'bukuBesarPdf'])->name('buku-besar.pdf');
        Route::get('/laba-rugi/pdf',   [LaporanController::class, 'labaRugiPdf'])->name('laba-rugi.pdf');
        Route::get('/neraca/pdf',      [LaporanController::class, 'neracaPdf'])->name('neraca.pdf');
    });
});
