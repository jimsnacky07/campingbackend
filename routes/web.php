<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\BarangController as AdminBarangController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\KategoriController as AdminKategoriController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\PembayaranController as AdminPembayaranController;
use App\Http\Controllers\Admin\PelangganController as AdminPelangganController;
use App\Http\Controllers\Admin\PengembalianController as AdminPengembalianController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
        Route::get('/register', [AdminAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('/register', [AdminAuthController::class, 'register'])->name('register.post');
    });

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

        Route::resource('kategori', AdminKategoriController::class)->except(['show']);
        Route::resource('barang', AdminBarangController::class)->except(['show']);
        Route::get('pelanggan', [AdminPelangganController::class, 'index'])->name('pelanggan.index');
        Route::get('pelanggan/{pelanggan}', [AdminPelangganController::class, 'show'])->name('pelanggan.show');

        Route::get('transaksi', [AdminTransaksiController::class, 'index'])->name('transaksi.index');
        Route::get('transaksi/{sewa}', [AdminTransaksiController::class, 'show'])->name('transaksi.show');
        Route::post('transaksi/{sewa}/status', [AdminTransaksiController::class, 'updateStatus'])->name('transaksi.update-status');

        Route::get('pembayaran', [AdminPembayaranController::class, 'index'])->name('pembayaran.index');
        Route::get('pembayaran/{sewa}', [AdminPembayaranController::class, 'show'])->name('pembayaran.show');
        Route::post('pembayaran/{sewa}/validasi', [AdminPembayaranController::class, 'validatePayment'])->name('pembayaran.validate');

        Route::get('pengembalian', [AdminPengembalianController::class, 'index'])->name('pengembalian.index');
        Route::get('pengembalian/create', [AdminPengembalianController::class, 'create'])->name('pengembalian.create');
        Route::post('pengembalian', [AdminPengembalianController::class, 'store'])->name('pengembalian.store');

        Route::get('laporan/penyewaan', [AdminLaporanController::class, 'penyewaan'])->name('laporan.penyewaan');
        Route::get('laporan/pendapatan', [AdminLaporanController::class, 'pendapatan'])->name('laporan.pendapatan');
        Route::get('laporan/barang-terlaris', [AdminLaporanController::class, 'barangTerlaris'])->name('laporan.barang-terlaris');
    });
});
