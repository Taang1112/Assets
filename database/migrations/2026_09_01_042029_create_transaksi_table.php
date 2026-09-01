<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transaksi', function (Blueprint $table) {
            $table->increments('transaksi_id');

            $table->unsignedInteger('produk_id');
            $table->unsignedInteger('pelanggan_id');
            $table->unsignedInteger('karyawan_id');

            $table->string('kode_transaksi', 30)->unique();
            $table->dateTime('tanggal_transaksi');
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 15, 2);
            $table->decimal('total_harga', 15, 2);

            $table->enum('metode_pembayaran', [
                'Cash',
                'Transfer',
                'QRIS',
                'E-Wallet'
            ]);

            $table->enum('status', [
                'Pending',
                'Selesai',
                'Batal'
            ])->default('Pending');

            $table->foreign('produk_id')
                ->references('produk_id')
                ->on('produk')
                ->onDelete('cascade');

            $table->foreign('pelanggan_id')
                ->references('pelanggan_id')
                ->on('pelanggan')
                ->onDelete('cascade');

            $table->foreign('karyawan_id')
                ->references('karyawan_id')
                ->on('karyawan')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transaksi');
    }
};