<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok', function (Blueprint $table) {
            $table->increments('stok_id');

            $table->unsignedInteger('produk_id');
            $table->unsignedInteger('supplier_id');
            $table->unsignedInteger('karyawan_id');

            $table->dateTime('tanggal_masuk');
            $table->integer('stok_lama');
            $table->integer('stok_masuk');
            $table->decimal('harga_lama', 15, 2);
            $table->decimal('harga_beli', 15, 2);
            $table->text('keterangan')->nullable();

            $table->foreign('produk_id')
                ->references('produk_id')
                ->on('produk')
                ->onDelete('restrict');

            $table->foreign('supplier_id')
                ->references('supplier_id')
                ->on('supplier')
                ->onDelete('restrict');

            $table->foreign('karyawan_id')
                ->references('karyawan_id')
                ->on('karyawan')
                ->onDelete('restrict');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok');
    }
};
