<?php

use App\Http\Controllers\InventarisController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KategoriInventarisController;
use App\Http\Controllers\KategoriProdukController;
use App\Http\Controllers\PelangganController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('transaksi.index'));

Route::resource('karyawan',           KaryawanController::class);
Route::resource('kategori-produk',    KategoriProdukController::class);
Route::resource('kategori-inventaris', KategoriInventarisController::class)->parameters([
    'kategori-inventaris' => 'kategori_inventaris'
]);
Route::resource('produk',             ProdukController::class);
Route::resource('inventaris',         InventarisController::class)->parameters([
    'inventaris' => 'inventaris'
]);
Route::resource('pelanggan',          PelangganController::class);
Route::resource('transaksi',          TransaksiController::class);
