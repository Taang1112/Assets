<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Produk;
use App\Models\Stok;
use App\Models\Supplier;
use App\Models\Karyawan;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tambah kolom stok_tersisa dan harga_jual ke tabel stok
        Schema::table('stok', function (Blueprint $table) {
            if (!Schema::hasColumn('stok', 'stok_tersisa')) {
                $table->integer('stok_tersisa')->default(0)->after('stok_masuk');
            }
            if (!Schema::hasColumn('stok', 'harga_jual')) {
                $table->decimal('harga_jual', 15, 2)->default(0)->after('harga_beli');
            }
        });

        // 2. Buat tabel transaksi_batch
        if (!Schema::hasTable('transaksi_batch')) {
            Schema::create('transaksi_batch', function (Blueprint $table) {
                $table->increments('transaksi_batch_id');
                $table->unsignedInteger('transaksi_id');
                $table->unsignedInteger('stok_id');
                $table->integer('jumlah');
                $table->decimal('harga_beli', 15, 2);
                $table->decimal('harga_jual', 15, 2);
                $table->decimal('subtotal', 15, 2);
                $table->timestamps();

                $table->foreign('transaksi_id')
                    ->references('transaksi_id')
                    ->on('transaksi')
                    ->onDelete('restrict');

                $table->foreign('stok_id')
                    ->references('stok_id')
                    ->on('stok')
                    ->onDelete('restrict');
            });
        }

        // 3. Initial Adjustment Batch untuk data existing (Idempotent & Aman)
        $defaultSupplier = Supplier::first();
        $defaultKaryawan = Karyawan::first();

        if ($defaultSupplier && $defaultKaryawan) {
            $produks = Produk::all();
            foreach ($produks as $produk) {
                // Cek apakah produk ini sudah memiliki batch inisialisasi / batch aktif agar tidak duplikat (Idempotent)
                $existingBatchCount = Stok::where('produk_id', $produk->produk_id)->count();

                if ($existingBatchCount === 0 && $produk->stok > 0) {
                    Stok::create([
                        'produk_id'     => $produk->produk_id,
                        'supplier_id'   => $defaultSupplier->supplier_id,
                        'karyawan_id'   => $defaultKaryawan->karyawan_id,
                        'tanggal_masuk' => now(),
                        'stok_lama'     => 0,
                        'stok_masuk'    => $produk->stok,
                        'stok_tersisa'  => $produk->stok, // Sesuai aturan: stok_tersisa = stok_masuk
                        'harga_lama'    => 0,
                        'harga_beli'    => $produk->harga_beli,
                        'harga_jual'    => $produk->harga_jual,
                        'keterangan'    => 'Inisialisasi Stok Awal (Adjustment Batch FIFO)',
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi_batch');

        Schema::table('stok', function (Blueprint $table) {
            if (Schema::hasColumn('stok', 'stok_tersisa')) {
                $table->dropColumn('stok_tersisa');
            }
            if (Schema::hasColumn('stok', 'harga_jual')) {
                $table->dropColumn('harga_jual');
            }
        });
    }
};
