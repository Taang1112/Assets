<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Stat Cards
        $totalProduk      = Produk::count();
        $totalStok        = Produk::sum('stok');
        $stokRendahCount  = Produk::where('stok', '<=', 5)->count();
        $totalSupplier    = Supplier::count();
        $totalPelanggan   = Pelanggan::count();
        $totalTransaksi   = Transaksi::count();

        // 2. Produk Stok Rendah (stok <= 5)
        $produkStokRendah = Produk::with('kategori')
            ->where('stok', '<=', 5)
            ->orderBy('stok', 'asc')
            ->take(10)
            ->get();

        // 3. Transaksi Terbaru (Maksimal 10)
        $transaksiTerbaru = Transaksi::with(['produk', 'pelanggan', 'karyawan'])
            ->latest()
            ->take(10)
            ->get();

        // 4. Ringkasan Penjualan (Hanya status Selesai)
        $penjualanHariIni  = Transaksi::where('status', 'Selesai')
            ->whereDate('tanggal_transaksi', today())
            ->sum('total_harga');

        $penjualanBulanIni = Transaksi::where('status', 'Selesai')
            ->whereYear('tanggal_transaksi', now()->year)
            ->whereMonth('tanggal_transaksi', now()->month)
            ->sum('total_harga');

        $transaksiSelesai  = Transaksi::where('status', 'Selesai')->count();
        $transaksiPending  = Transaksi::where('status', 'Pending')->count();
        $transaksiBatal    = Transaksi::where('status', 'Batal')->count();

        // 5. Produk Dengan Stok Terbanyak
        $produkStokTerbanyak = Produk::orderBy('stok', 'desc')
            ->take(5)
            ->get();

        // 6. FIFO / Batch Summary
        $totalBatchAktif     = Stok::where('stok_tersisa', '>', 0)->count();
        $totalStokBatchAktif = Stok::where('stok_tersisa', '>', 0)->sum('stok_tersisa');
        $batchHampirHabis    = Stok::with(['produk', 'supplier'])
            ->where('stok_tersisa', '>', 0)
            ->where('stok_tersisa', '<=', 5)
            ->orderBy('stok_tersisa', 'asc')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProduk',
            'totalStok',
            'stokRendahCount',
            'totalSupplier',
            'totalPelanggan',
            'totalTransaksi',
            'produkStokRendah',
            'transaksiTerbaru',
            'penjualanHariIni',
            'penjualanBulanIni',
            'transaksiSelesai',
            'transaksiPending',
            'transaksiBatal',
            'produkStokTerbanyak',
            'totalBatchAktif',
            'totalStokBatchAktif',
            'batchHampirHabis'
        ));
    }
}
