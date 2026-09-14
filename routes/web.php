<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventarisController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriInventarisController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\StokController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Operational Routes — Shared between Admin & Petugas
    Route::middleware('role:admin,petugas')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::resource('transaksi', TransaksiController::class);
        Route::resource('produk', ProdukController::class);
        Route::resource('pelanggan', PelangganController::class);
        Route::resource('supplier', SupplierController::class);
        Route::resource('stok', StokController::class)->only(['index', 'create', 'store']);
    });

    // Admin Only Routes — Forbidden for Petugas
    Route::middleware('role:admin')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('karyawan', KaryawanController::class);
        Route::resource('inventaris', InventarisController::class)->parameters([
            'inventaris' => 'inventaris'
        ]);
        Route::resource('kategori-produk', KategoriProdukController::class);
        Route::resource('kategori-inventaris', KategoriInventarisController::class)->parameters([
            'kategori-inventaris' => 'kategori_inventaris'
        ]);
    });
});
