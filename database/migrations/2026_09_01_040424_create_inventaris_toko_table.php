<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaris', function (Blueprint $table) {
            $table->increments('inventaris_id');

            $table->unsignedInteger('kategori_inventaris_id');

            $table->string('kode_inventaris', 20)->unique();
            $table->string('nama_inventaris', 100);
            $table->integer('jumlah')->default(1);

            $table->enum('kondisi', [
                'Baik',
                'Rusak Ringan',
                'Rusak Berat'
            ])->default('Baik');

            $table->string('lokasi', 100);
            $table->date('tanggal_masuk');
            $table->decimal('harga_perolehan', 15, 2);

            $table->enum('status', [
                'Tersedia',
                'Dipakai',
                'Dipinjam',
                'Dihapus'
            ])->default('Tersedia');

            $table->foreign('kategori_inventaris_id')
                ->references('kategori_inventaris_id')
                ->on('kategori_inventaris')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaris');
    }
};