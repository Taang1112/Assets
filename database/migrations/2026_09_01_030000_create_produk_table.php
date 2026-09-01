<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->increments('produk_id');

            $table->unsignedInteger('kategori_produk_id');

            $table->string('kode_produk', 20)->unique();
            $table->string('nama_produk', 100);
            $table->text('deskripsi')->nullable();
            $table->decimal('harga_beli', 15, 2);
            $table->decimal('harga_jual', 15, 2);
            $table->integer('stok')->default(0);
            $table->string('satuan', 20);
            $table->enum('status', ['Aktif', 'Tidak Aktif'])->default('Aktif');

            $table->foreign('kategori_produk_id')
                ->references('kategori_produk_id')
                ->on('kategori_produk')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('produk');
    }
};
