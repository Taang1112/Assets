<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Produk;
use App\Models\Stok;
use App\Models\Transaksi;
use App\Models\TransaksiBatch;
use App\Models\Pelanggan;
use App\Models\Karyawan;
use App\Models\KategoriProduk;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

echo "=== STARTING FIFO TEST SUITE ===\n";

$results = [
    'fifo_allocation'    => false,
    'insufficient_stock' => false,
    'price_manipulation' => false,
    'reversal'           => false,
];

try {
    DB::beginTransaction();

    // Setup prerequisites
    $kategori = KategoriProduk::first() ?? KategoriProduk::create(['kode_kategori' => 'KAT-TEST', 'nama_kategori' => 'Test Cat', 'deskripsi' => 'Test', 'status' => 'Aktif']);
    $supplier = Supplier::first() ?? Supplier::create(['kode_supplier' => 'SUP-TEST', 'nama_supplier' => 'Test Supp', 'no_telepon' => '0812345678', 'alamat' => 'Test', 'status' => 'Aktif']);
    $pelanggan = Pelanggan::first() ?? Pelanggan::create(['kode_pelanggan' => 'PEL-TEST', 'nama_pelanggan' => 'Test Customer', 'no_telepon' => '0812345678', 'status' => 'Aktif']);
    $karyawan = Karyawan::first() ?? Karyawan::create(['kode_karyawan' => 'KAR-TEST', 'nik' => '99999999', 'nama_lengkap' => 'Test Karyawan', 'jabatan' => 'Petugas', 'status' => 'Aktif']);

    // 1. Create Test Product
    $produk = Produk::create([
        'kategori_produk_id' => $kategori->kategori_produk_id,
        'kode_produk'        => 'PRD-TF-' . rand(1000, 9999),
        'nama_produk'        => 'Test FIFO Product',
        'deskripsi'          => 'Test product for FIFO validation',
        'harga_beli'         => 5000,
        'harga_jual'         => 7000,
        'stok'               => 20,
        'satuan'             => 'pcs',
        'status'             => 'Aktif',
    ]);

    // 2. Create Batch A (10 pcs @ 5,000 / 7,000)
    $batchA = Stok::create([
        'produk_id'     => $produk->produk_id,
        'supplier_id'   => $supplier->supplier_id,
        'karyawan_id'   => $karyawan->karyawan_id,
        'tanggal_masuk' => now()->subDays(2),
        'stok_lama'     => 0,
        'stok_masuk'    => 10,
        'stok_tersisa'  => 10,
        'harga_lama'    => 0,
        'harga_beli'    => 5000,
        'harga_jual'    => 7000,
        'keterangan'    => 'Batch A Test',
    ]);

    // 3. Create Batch B (10 pcs @ 6,000 / 8,000)
    $batchB = Stok::create([
        'produk_id'     => $produk->produk_id,
        'supplier_id'   => $supplier->supplier_id,
        'karyawan_id'   => $karyawan->karyawan_id,
        'tanggal_masuk' => now()->subDay(),
        'stok_lama'     => 10,
        'stok_masuk'    => 10,
        'stok_tersisa'  => 10,
        'harga_lama'    => 7000,
        'harga_beli'    => 6000,
        'harga_jual'    => 8000,
        'keterangan'    => 'Batch B Test',
    ]);

    echo "[INFO] Test product created. Initial stock: {$produk->stok}. Batch A: 10@7000, Batch B: 10@8000\n";

    // TEST 1: FIFO Allocation & Price Manipulation
    $controller = new App\Http\Controllers\TransaksiController();

    $requestStore = new Illuminate\Http\Request([
        'produk_id'         => $produk->produk_id,
        'pelanggan_id'      => $pelanggan->pelanggan_id,
        'karyawan_id'       => $karyawan->karyawan_id,
        'tanggal_transaksi' => now()->format('Y-m-d H:i:s'),
        'jumlah'            => 15,
        'harga_satuan'      => 1, // Manipulated price input from client
        'total_harga'       => 1, // Manipulated price input from client
        'metode_pembayaran' => 'Cash',
        'status'            => 'Selesai',
    ]);

    $controller->store($requestStore);

    $trx1 = Transaksi::latest('transaksi_id')->first();
    $batchARefresh = Stok::find($batchA->stok_id);
    $batchBRefresh = Stok::find($batchB->stok_id);
    $produkRefresh = Produk::find($produk->produk_id);
    $tbRows        = TransaksiBatch::where('transaksi_id', $trx1->transaksi_id)->get();

    echo "\n--- TEST 1 RESULTS ---\n";
    echo "Trx Kode: {$trx1->kode_transaksi}\n";
    echo "Trx harga_satuan: {$trx1->harga_satuan} (Expected: 7000)\n";
    echo "Trx total_harga: {$trx1->total_harga} (Expected: 110000)\n";
    echo "Batch A stok_tersisa: {$batchARefresh->stok_tersisa} (Expected: 0)\n";
    echo "Batch B stok_tersisa: {$batchBRefresh->stok_tersisa} (Expected: 5)\n";
    echo "Produk stok: {$produkRefresh->stok} (Expected: 5)\n";
    echo "TransaksiBatch rows count: {$tbRows->count()} (Expected: 2)\n";

    if (
        (float)$trx1->harga_satuan == 7000.0 &&
        (float)$trx1->total_harga == 110000.0 &&
        $batchARefresh->stok_tersisa == 0 &&
        $batchBRefresh->stok_tersisa == 5 &&
        $produkRefresh->stok == 5 &&
        $tbRows->count() == 2
    ) {
        $results['fifo_allocation'] = true;
        $results['price_manipulation'] = true;
        echo "[PASS] TEST 1 (FIFO Allocation & Price Manipulation) PASSED!\n";
    } else {
        echo "[FAIL] TEST 1 FAILED!\n";
    }

    // TEST 2: Insufficient Stock Rejection
    echo "\n--- TEST 2: Insufficient Stock (Sell 10 when stock is 5) ---\n";
    $requestOverflow = new Illuminate\Http\Request([
        'produk_id'         => $produk->produk_id,
        'pelanggan_id'      => $pelanggan->pelanggan_id,
        'karyawan_id'       => $karyawan->karyawan_id,
        'tanggal_transaksi' => now()->format('Y-m-d H:i:s'),
        'jumlah'            => 10,
        'metode_pembayaran' => 'Cash',
        'status'            => 'Selesai',
    ]);

    $responseOverflow = $controller->store($requestOverflow);
    $batchARefresh2 = Stok::find($batchA->stok_id);
    $batchBRefresh2 = Stok::find($batchB->stok_id);
    $produkRefresh2 = Produk::find($produk->produk_id);

    echo "Batch A stok_tersisa after rejected store: {$batchARefresh2->stok_tersisa} (Expected: 0)\n";
    echo "Batch B stok_tersisa after rejected store: {$batchBRefresh2->stok_tersisa} (Expected: 5)\n";
    echo "Produk stok after rejected store: {$produkRefresh2->stok} (Expected: 5)\n";

    if ($batchARefresh2->stok_tersisa == 0 && $batchBRefresh2->stok_tersisa == 5 && $produkRefresh2->stok == 5) {
        $results['insufficient_stock'] = true;
        echo "[PASS] TEST 2 (Insufficient Stock Rejection & Rollback) PASSED!\n";
    } else {
        echo "[FAIL] TEST 2 FAILED!\n";
    }

    // TEST 3: Reversal / Pembatalan
    echo "\n--- TEST 3: Reversal / Deletion of Completed Transaction ---\n";
    $controller->destroy($trx1);

    $batchARefresh3 = Stok::find($batchA->stok_id);
    $batchBRefresh3 = Stok::find($batchB->stok_id);
    $produkRefresh3 = Produk::find($produk->produk_id);
    $tbRowsAfterDel = TransaksiBatch::where('transaksi_id', $trx1->transaksi_id)->get();

    echo "Batch A stok_tersisa after destroy: {$batchARefresh3->stok_tersisa} (Expected: 10)\n";
    echo "Batch B stok_tersisa after destroy: {$batchBRefresh3->stok_tersisa} (Expected: 10)\n";
    echo "Produk stok after destroy: {$produkRefresh3->stok} (Expected: 20)\n";
    echo "TransaksiBatch rows after destroy: {$tbRowsAfterDel->count()} (Expected: 0)\n";

    if ($batchARefresh3->stok_tersisa == 10 && $batchBRefresh3->stok_tersisa == 10 && $produkRefresh3->stok == 20 && $tbRowsAfterDel->count() == 0) {
        $results['reversal'] = true;
        echo "[PASS] TEST 3 (FIFO Reversal on Destroy) PASSED!\n";
    } else {
        echo "[FAIL] TEST 3 FAILED!\n";
    }

} catch (\Throwable $e) {
    echo "[ERROR] Exception caught: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
} finally {
    DB::rollBack();
    echo "\n=== ALL DATABASE CHANGES ROLLED BACK SAFELY ===\n";
    echo "SUMMARY: " . json_encode($results, JSON_PRETTY_PRINT) . "\n";
}
