<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\MetodePembayaranController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\SewaController;
use App\Http\Controllers\MidtransController;
use App\Http\Controllers\KeranjangController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/reset-password', [AuthController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/logout', [AuthController::class, 'logout']);
    });
});

// Public routes for Kategori & Barang
Route::get('/kategori', [KategoriController::class, 'index']);
Route::get('/kategori/{kategori}', [KategoriController::class, 'show']);
Route::get('/barang', [BarangController::class, 'index']);
Route::get('/barang/{barang}', [BarangController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    // Protected Master data (CRUD oleh admin)
    Route::post('/kategori', [KategoriController::class, 'store']);
    Route::put('/kategori/{kategori}', [KategoriController::class, 'update']);
    Route::delete('/kategori/{kategori}', [KategoriController::class, 'destroy']);

    Route::post('/barang', [BarangController::class, 'store']);
    Route::put('/barang/{barang}', [BarangController::class, 'update']);
    Route::delete('/barang/{barang}', [BarangController::class, 'destroy']);

    // Metode pembayaran (list untuk user, CRUD oleh admin)
    Route::get('/metode-pembayaran', [MetodePembayaranController::class, 'index']);
    Route::post('/metode-pembayaran', [MetodePembayaranController::class, 'store']);
    Route::get('/metode-pembayaran/{metodePembayaran}', [MetodePembayaranController::class, 'show']);
    Route::put('/metode-pembayaran/{metodePembayaran}', [MetodePembayaranController::class, 'update']);
    Route::delete('/metode-pembayaran/{metodePembayaran}', [MetodePembayaranController::class, 'destroy']);

    // Pelanggan (khusus admin)
    Route::get('/pelanggan', [PelangganController::class, 'index']);
    Route::get('/pelanggan/{pelanggan}', [PelangganController::class, 'show']);
    Route::put('/pelanggan/{pelanggan}', [PelangganController::class, 'update']);
    Route::delete('/pelanggan/{pelanggan}', [PelangganController::class, 'destroy']);

    // Sewa & keranjang sederhana
    Route::get('/sewa', [SewaController::class, 'index']);
    Route::get('/sewa/me', [SewaController::class, 'mySewa']);
    Route::post('/sewa', [SewaController::class, 'store']);
    Route::get('/sewa/{sewa}', [SewaController::class, 'show']);
    Route::put('/sewa/{sewa}/status', [SewaController::class, 'updateStatus']);

    // Pembayaran
    Route::post('/sewa/{sewa}/upload-bukti', [PembayaranController::class, 'uploadBukti']);
    Route::put('/sewa/{sewa}/validasi', [PembayaranController::class, 'validasi']);

    // Pengembalian
    Route::get('/pengembalian', [PengembalianController::class, 'index']);
    Route::post('/pengembalian', [PengembalianController::class, 'store']);
    Route::get('/pengembalian/{pengembalian}', [PengembalianController::class, 'show']);
    Route::put('/pengembalian/{pengembalian}', [PengembalianController::class, 'update']);

    // Laporan
    Route::get('/laporan/penyewaan', [LaporanController::class, 'penyewaan']);
    Route::get('/laporan/pendapatan-bulanan', [LaporanController::class, 'pendapatanBulanan']);
    Route::get('/laporan/barang-terlaris', [LaporanController::class, 'barangTerlaris']);
    
    // Export PDF
    Route::get('/sewa/{sewa}/invoice', [LaporanController::class, 'exportInvoice']);
    Route::get('/laporan/penyewaan/pdf', [LaporanController::class, 'exportPenyewaan']);

    // Midtrans Payment
    Route::prefix('payment')->group(function () {
        Route::post('/create-transaction', [MidtransController::class, 'createTransaction']);
        Route::get('/status/{order_id}', [MidtransController::class, 'checkStatus']);
    });

    // Keranjang
    Route::prefix('keranjang')->group(function () {
        Route::get('/', [KeranjangController::class, 'index']);
        Route::post('/', [KeranjangController::class, 'store']);
        Route::put('/{id_barang}', [KeranjangController::class, 'update']);
        Route::delete('/{id_barang}', [KeranjangController::class, 'destroy']);
        Route::delete('/', [KeranjangController::class, 'clear']);
    });
});

// Midtrans Notification (Public)
Route::post('/payment/notification', [MidtransController::class, 'handleNotification']);


